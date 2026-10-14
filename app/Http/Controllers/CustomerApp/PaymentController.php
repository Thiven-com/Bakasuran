<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /** Capture a payment (called after successful payment) */
    public function capture(Request $request, $paymentId)
    {
        $user = auth('sanctum')->user();

        $data = $request->validate([
            'provider_payment_id' => 'required|string',
            'provider_order_id'   => 'sometimes|string',
            'provider_signature'  => 'sometimes|string',
        ]);

        /** @var Payment $payment */
        $payment = Payment::findOrFail($paymentId);

        if ($payment->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($payment->status === 'paid') {
            return response()->json(['message' => 'Already captured'], 200);
        }

        // ✅ Mark payment as captured
        $payment->status              = 'paid';
        $payment->provider_payment_id = $data['provider_payment_id'];
        $payment->provider_order_id   = $data['provider_order_id'] ?? null;
        $payment->provider_signature  = $data['provider_signature'] ?? null;
        $payment->paid_at             = now();
        $payment->save();

        // ✅ Update order payment_status
        $order = $payment->order;
        if ($order) {
            $order->payment_status = 'paid';
            $order->status         = 'confirmed'; // optional workflow step
            $order->save();
        }

        return response()->json([
            'message' => 'Payment captured',
            'payment' => [
                'id'       => $payment->id,
                'status'   => $payment->status,
                'amount'   => $payment->amount,
                'method'   => $payment->method,
                'provider' => $payment->provider,
                'paid_at'  => $payment->paid_at,
            ],
            'order'   => [
                'id'             => $order?->id,
                'payment_status' => $order?->payment_status,
                'status'         => $order?->status,
            ]
        ]);
    }

}
