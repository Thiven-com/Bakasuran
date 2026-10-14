<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlacedMail;
use App\Models\CouponUsageHistory;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use App\Models\Payment;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class WebhookController extends Controller
{
    public function razorpay(Request $request)
    {
        $webhookSecret = '54e87af896ebf7202678a25dbc4d738ff0a01276c5648d9d49c73ffe0ffa4807';
        $keyId = 'rzp_test_R9GdWcNAde0fOH';
        $keySecret = 'EfDOgPQMM170Rv6ENjAaqsyM';

        // Raw payload required for signature verification
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature') ?? $request->header('x-razorpay-signature');

        // Verify signature
        $expected = hash_hmac('sha256', $payload, $webhookSecret);
        if (!hash_equals($expected, $signature)) {
            Log::warning('Razorpay webhook signature mismatch', [
                'expected' => $expected,
                'received' => $signature
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $data = json_decode($payload, true);
        $event = $data['event'] ?? null;

        // Only handle events we care about
        if ($event === 'payment.authorized' || $event === 'payment.captured') {
            $paymentEntity = $data['payload']['payment']['entity'] ?? null;

            if (!$paymentEntity) {
                Log::warning('Razorpay webhook: missing payment entity', ['payload' => $data['payload'] ?? null]);
                return response()->json(['error' => 'Bad payload'], 400);
            }

            $providerPaymentId = $paymentEntity['id'] ?? null;      // pay_...
            $providerOrderId = $paymentEntity['order_id'] ?? null; // order_...
            $providerAmount = $paymentEntity['amount'] ?? null;   // in paise
            // notes may contain your internal mapping
            $notes = $paymentEntity['notes'] ?? [];
            $internalPaymentId = $notes['payment_id'] ?? null;

            // Try to find internal Payment record
            $payment = null;
            if ($internalPaymentId) {
                $payment = Payment::find($internalPaymentId);
            }

            if (!$payment && $providerOrderId) {
                $payment = Payment::where('provider_order_id', $providerOrderId)->first();
            }

            if (!$payment && $providerPaymentId) {
                $payment = Payment::where('provider_payment_id', $providerPaymentId)->first();
            }

            if (!$payment) {
                Log::error('Razorpay webhook: Payment not found', [
                    'provider_payment_id' => $providerPaymentId,
                    'provider_order_id' => $providerOrderId,
                    'notes_payment_id' => $internalPaymentId
                ]);
                // You can choose to return 200 to stop retries, or 404 to debug.
                // Returning 200 to avoid aggressive retries, but logged for investigation.
                return response()->json(['error' => 'Payment not found'], 200);
            }

            // Idempotency: if already marked paid, reply OK
            if ($payment->status === 'paid') {
                Log::info('Razorpay webhook: payment already paid', ['payment_id' => $payment->id]);
                return response()->json(['message' => 'Already captured'], 200);
            }

            // If event is 'payment.authorized' we should capture it via API
            if ($event === 'payment.authorized') {
                // Capture using Razorpay API
                try {
                    $api = new Api($keyId, $keySecret);

                    // Ensure amount to capture is provided. Use providerAmount (paise)
                    // Some flows prefer to capture a different amount; here we capture full amount.
                    if (!$providerAmount) {
                        Log::error('Razorpay webhook: provider amount missing for capture', ['provider_payment_id' => $providerPaymentId]);
                        return response()->json(['error' => 'Amount missing'], 400);
                    }

                    // Fetch and capture payment
                    $razorpayPayment = $api->payment->fetch($providerPaymentId);
                    // capture expects amount in paise (integer). Provide amount as integer.
                    $captureResponse = $razorpayPayment->capture(['amount' => (int) $providerAmount]);

                    // After successful capture, the API returns captured payment details.
                    // Update internal DB below using the captureResponse data if needed.
                    $providerPaymentId = $captureResponse['id'] ?? $providerPaymentId;
                    $providerOrderId = $captureResponse['order_id'] ?? $providerOrderId;
                    $providerSignature = $signature;
                } catch (\Exception $e) {
                    // Log and return 500 so you can retry/inspect; but careful: Razorpay will retry for non-2xx.
                    Log::error('Razorpay capture failed', [
                        'error' => $e->getMessage(),
                        'provider_payment_id' => $providerPaymentId
                    ]);
                    // Option: return 500 to signal a retry. Alternatively return 200 and investigate separately.
                    return response()->json(['error' => 'Capture failed'], 500);
                }
            } else {
                // If event is payment.captured, data already captured by Razorpay
                $providerSignature = $signature;
            }

            // Persist capture result into DB (idempotent, inside transaction)
            DB::transaction(function () use ($payment, $providerPaymentId, $providerOrderId, $providerSignature, $data) {
                $payment->status = 'paid';
                $payment->provider_payment_id = $providerPaymentId;
                $payment->provider_order_id = $providerOrderId ?? ($data['payload']['payment']['entity']['order_id'] ?? null);
                $payment->provider_signature = $providerSignature;
                $payment->paid_at = now();
                $payment->save();

                // Update related order
                $order = $payment->order; // assumes relation Payment->order exists
                if ($order) {
                    $order->payment_status = 'paid';
                    $order->status = 'placed'; // adjust as per your workflow
                    // $order->status = 'confirmed'; // adjust as per your workflow
                    $order->save();

                    // ---- Order status history
                    $orderstatushistory = new OrderStatusHistory();
                    $orderstatushistory->order_id = $order->id;
                    $orderstatushistory->status = 'placed';
                    $orderstatushistory->remark = 'Order placed via Razorpay webhook';
                    $orderstatushistory->save();
                }

                $user = Customer::where('id', $order->customer_id)->first();

                if ($order->discount_total > 0) {
                    $history = new CouponUsageHistory();
                    $history->coupon_id = $order->coupon_id;
                    $history->user_id = $user->id;
                    $history->order_id = $order->id;
                    $history->discount_amount = $order->discount_total;
                    $history->used_at = Carbon::now();
                    $history->save();
                }
                try {
                    $toDelete = \App\Models\CartItem::where('user_id', $user->id)->where('type', $order->order_type)->get();
                    foreach ($toDelete as $ci) {
                        $ci->delete();
                    }
                } catch (\Exception $e) {
                }
                if (!empty($user->email)) {
                    try {
                        Mail::to($user->email)
                            ->send(new OrderPlacedMail($order));
                    } catch (\Exception $e) {
                        Log::error('Mail failed: ' . $e->getMessage());
                    }
                }
                // try {
                //     $user = Customer::where('id', $order->customer_id)->first();
                //     $name = $user->name ?? 'Customer';
                //     $invoiceId = $order->invoice_id;
                //     $amount = $order->grand_total;
                //     $message = $this->sendWhatsAppMessage(
                //         $user->mobile,
                //         'order_confirmation',
                //         [
                //             'field_1' => $name,
                //             'field_2' => $invoiceId,
                //             'field_3' => $amount,
                //         ]
                //     );
                //     $whatsappService = new WhatsAppService();
                //     $result = $whatsappService->sendTemplateMessage($message);
                // } catch (\Exception $e) {
                //     $result = false;
                //     Log::info($e->getMessage());
                // }
            });

            Log::info('Razorpay webhook: payment processed', [
                'payment_id' => $payment->id,
                'provider_payment_id' => $providerPaymentId,
                'event' => $event
            ]);



            return response()->json(['status' => 'ok'], 200);
        }

        // Unhandled event types — respond 200 to acknowledge
        Log::info('Razorpay webhook: unhandled event', ['event' => $event]);
        return response()->json(['status' => 'ignored'], 200);
    }



    public function delhivery(Request $request)
    {
        // if ($request->header('X-Webhook-Secret') !== env('DELHIVERY_WEBHOOK_SECRET')) {
        //     Log::warning('Unauthorized webhook attempt', [
        //         'headers' => $request->headers->all()
        //     ]);

        //     return response()->json(['status' => false, 'message' => 'Unauthorized']);
        // }
        try {
            $payload = $request->all();
            Log::info('Delhivery Webhook Received', $payload);

            if (!isset($payload['waybill'])) {
                return response()->json(['status' => false, 'message' => 'Invalid payload']);
            }

            $awb = $payload['waybill'];

            $order = Order::where('awb', $awb)->first();

            if (!$order) {
                Log::warning("Order not found for AWB: {$awb}");
                return response()->json(['status' => false, 'message' => 'Order not found']);
            }

            $status = strtolower($payload['status'] ?? '');

            // ✅ Map status
            if ($status === 'delivered') {
                $order->status = 'delivered';
            } elseif (in_array($status, ['in transit', 'shipped'])) {
                $order->status = 'shipped';
            } elseif (in_array($status, ['rto', 'returned'])) {
                $order->status = 'returned';
            }

            $order->shipment_status = 'Success';
            $order->shipment_message = $payload['status'] ?? '';
            $order->shipment_response = json_encode($payload);

            $order->save();
            try {
                if ($order->status !== $payload['status']) {

                    $orderstatushistory = new OrderStatusHistory();
                    $orderstatushistory->order_id = $order->id;
                    $orderstatushistory->status = $payload['status'] ?? null;
                    $orderstatushistory->remark = 'Status updated to ' . ($payload['status'] ?? 'N/A') . ' via webhook delivery';
                    $orderstatushistory->save();
                }
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }

            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            Log::error('Webhook Error', ['error' => $e->getMessage()]);
            return response()->json(['status' => false]);
        }
    }

    private function sendWhatsAppMessage($cust_mobile, $templateName, array $fields = [])
    {

        $data = [
            "from_phone_number_id" => "1070303949494176",
            "phone_number" => '91' . $cust_mobile,
            "template_name" => $templateName,
            "template_language" => "en_Us",
            "header_image" => "https://cdn.pixabay.com/photo/2015/01/07/15/51/woman-591576_1280.jpg",
            "header_video" => "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
            "header_document" => "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
            "header_document_name" => "",
            "header_field_1" => "{full_name}",
            "location_latitude" => "",
            "location_longitude" => "",
            "location_name" => "",
            "location_address" => "",
            "field_1" => $fields['field_1'] ?? '',
            "field_2" => $fields['field_2'] ?? '',
            "field_3" => $fields['field_3'] ?? '',
            "field_4" => $fields['field_4'] ?? '',
            "field_5" => $fields['field_5'] ?? '',
            "button_0" => $fields['field_1'],
            "button_1" => "{phone_number}",
            "copy_code" => $fields['field_1'],
        ];

        return $data;
    }


    public function dtdcWebhook(Request $request)
    {
        Log::info('DTDC Webhook', $request->all());

        // $awb = $request->reference_number
        //     ?? $request->awb
        //     ?? $request->consignment_number;

        // $status = $request->status;

        // $order = Order::where('awb', $awb)->first();

        // if (!$order) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Order not found'
        //     ]);
        // }

        // $statusMap = [
        //     'Pickup Awaited' => 'pending',
        //     'Pickup Scheduled' => 'pending',
        //     'Pickup Completed' => 'shipping',
        //     'Held Up' => 'shipping',
        //     'In Transit' => 'shipping',
        //     'Out For Delivery' => 'out_for_delivery',
        //     'Delivered' => 'delivered',
        //     'Undelivered' => 'undelivered',
        //     'RTO' => 'returned',
        //     'Cancelled' => 'cancelled',
        // ];

        // $order->update([
        //     'shipment_status' => $status,
        //     'status' => $statusMap[$status] ?? $order->status,
        //     'shipment_response' => json_encode($request->all()),
        // ]);

        // try {
        //     if ($order->status !== $status) {

        //         $orderstatushistory = new OrderStatusHistory();
        //         $orderstatushistory->order_id = $order->id;
        //         $orderstatushistory->status = $status ?? null;
        //         $orderstatushistory->remark = 'Status updated to ' . ($status ?? 'N/A') . ' via DTDC webhook delivery';
        //         $orderstatushistory->save();
        //     }
        // } catch (\Exception $e) {
        //     Log::info($e->getMessage());
        // }

        // return response()->json([
        //     'success' => true
        // ]);
    }
}
