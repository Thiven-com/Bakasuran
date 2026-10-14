<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use RealRashid\SweetAlert\Facades\Alert;


class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|integer',
            'payment_method' => 'required|in:cod,online_payment',
        ]);

        $userId = Auth::guard('customer')->id();

        if (!$userId) {
            Alert::toast('Please login to continue.', 'warning');
            return redirect()->route('login');
        }

        $user = Customer::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        // Ensure the selected address belongs to this customer.
        $address = Address::where('id', $request->address_id)
            ->where('customer_id', $user->id)
            ->first();

        if (!$address) {
            Alert::toast('Please select a valid delivery address.', 'error');
            return back()->withInput();
        }

        $cartItems = CartItem::with('variant.product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            Alert::toast('Your cart is empty.', 'warning');
            return redirect()->route('cart');
        }

        foreach ($cartItems as $item) {
            if (!$item->variant || !$item->variant->product) {
                Alert::toast('A product in your cart is no longer available.', 'error');
                return redirect()->route('cart');
            }

            if (
                $item->quantity < 1 ||
                $item->quantity > $item->variant->stock
            ) {
                Alert::toast(
                    'Insufficient stock for ' . $item->variant->product->title,
                    'error'
                );
                return redirect()->route('cart');
            }
        }

        $shippingAddress = [
            'name' => $address->name ?? $user->name,
            'phone' => $address->mobile ?? $user->mobile,
            'mobile' => $address->mobile ?? $user->mobile,
            'email' => $address->email ?? $user->email,
            'address' => $address->address,
            'landmark' => $address->landmark,
            'address_2' => $address->address_2,
            'city' => $address->city,
            'state' => $address->state,
            'pincode' => $address->pincode,
        ];

        $subtotal = $cartItems->sum(function ($item) {
            return (float) $item->unit_price * (int) $item->quantity;
        });

        $shipping = 0;
        $discount = 0;
        $grandTotal = max(0, $subtotal + $shipping - $discount);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'customer_id' => $user->id,
                'invoice_id' => 'TEMP-' . uniqid(),
                'subtotal' => $subtotal,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_address' => $shippingAddress,
                'billing_address' => $shippingAddress,
            ]);

            $invoiceId = 'VS' . str_pad(
                $order->id,
                5,
                '0',
                STR_PAD_LEFT
            );

            $order->update(['invoice_id' => $invoiceId]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $item->product_variant_id,
                    'seller_id' => 0,
                    'product_title' => $item->variant->product->title,
                    'sku' => $item->variant->sku,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->unit_price * $item->quantity,
                ]);
            }

            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'amount' => $grandTotal,
                'currency' => 'INR',
                'status' => 'pending',
                'method' => $request->payment_method,
                'reference_no' => $invoiceId,
            ]);

            // Cash on Delivery
            if ($request->payment_method === 'cod') {
                $order->update([
                    'status' => 'placed',
                    'payment_status' => 'pending',
                ]);

                $payment->update([
                    'status' => 'pending',
                ]);

                foreach ($cartItems as $item) {
                    $item->variant->decrement(
                        'stock',
                        $item->quantity
                    );
                }

                CartItem::where('user_id', $user->id)->delete();

                DB::commit();

                Alert::success('Success', 'Your order has been placed.');

                return redirect()->route('customer.orders');
            }

            // Razorpay Online Payment

            // Razorpay Online Payment
            if ($request->payment_method === 'online_payment') {

                $gateway = PaymentGateway::where('code', 'razorpay')
                    ->where('is_online', 1)
                    ->where('status', 1)
                    ->first();

                if (!$gateway) {
                    throw new \RuntimeException(
                        'Razorpay gateway is disabled or not configured.'
                    );
                }

                // The config column contains JSON with key_id and secret.
                $gatewayConfig = is_array($gateway->config)
                    ? $gateway->config
                    : json_decode($gateway->config ?? '{}', true);

                $key = $gatewayConfig['key_id'] ?? null;
                $secret = $gatewayConfig['secret'] ?? null;

                if (empty($key) || empty($secret)) {
                    throw new \RuntimeException(
                        'Razorpay key_id or secret is missing from the gateway configuration.'
                    );
                }

                // Create Razorpay order.
                $api = new Api($key, $secret);

                $razorpayOrder = $api->order->create([
                    'receipt' => $invoiceId,
                    'amount' => (int) round($grandTotal * 100),
                    'currency' => 'INR',
                ]);

                // Save gateway details to the payment record.
                $payment->update([
                    'provider' => 'razorpay',
                    'provider_order_id' => $razorpayOrder['id'],
                ]);

                DB::commit();

                // Open the Razorpay payment page.
                return view('website.razorpay-payment', [
                    'order' => $order,
                    'payment' => $payment,
                    'razorpayOrder' => $razorpayOrder,
                    'grandTotal' => $grandTotal,
                    'user' => $user,
                    'razorpayKey' => $key,
                ]);
            }

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Checkout order creation failed', [
                'customer_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            Alert::toast(
                'Unable to place your order. Please try again.',
                'error'
            );

            return back()->withInput();
        }
    }

    public function orders()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login');
        }

        $orders = Order::where(
            'customer_id',
            Auth::guard('customer')->id()
        )->latest()->paginate(10);

        return view('website.orders', compact('orders'));
    }

    public function show($id)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login');
        }

        $order = Order::where('customer_id', Auth::guard('customer')->id())
            ->where('id', $id)
            ->firstOrFail();

        return view('website.order-details', compact('order'));
    }

    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $customerId = Auth::guard('customer')->id();

        if (!$customerId) {
            return redirect()->route('login')
                ->with('error', 'Please log in to verify your payment.');
        }

        try {
            $payment = Payment::where(
                'provider_order_id',
                $request->razorpay_order_id
            )
                ->where('user_id', $customerId)
                ->where('provider', 'razorpay')
                ->where('status', 'pending')
                ->firstOrFail();

            $gateway = PaymentGateway::where('code', 'razorpay')
                ->where('is_online', 1)
                ->where('status', 1)
                ->firstOrFail();

            $config = is_array($gateway->config)
                ? $gateway->config
                : json_decode($gateway->config, true);

            $key = $config['key_id'] ?? null;
            $secret = $config['secret'] ?? null;

            if (!$key || !$secret) {
                throw new \RuntimeException('Razorpay credentials are missing.');
            }

            $api = new Api($key, $secret);

            // Verify the payment signature before marking the order as paid.
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);

            DB::transaction(function () use ($payment, $request) {
                $payment->update([
                    'provider_payment_id' => $request->razorpay_payment_id,
                    'provider_signature' => $request->razorpay_signature,
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                $order = Order::where('id', $payment->order_id)
                    ->where('customer_id', $payment->user_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $order->update([
                    'status' => 'placed',
                    'payment_status' => 'paid',
                ]);

                // Remove only the purchased customer's cart items.
                CartItem::where('user_id', $payment->user_id)->delete();
            });

            Alert::success('Payment Successful', 'Your order has been placed.');

            return redirect()->route('customer.orders');
        } catch (\Throwable $e) {
            Log::error('Razorpay payment verification failed', [
                'customer_id' => $customerId,
                'razorpay_order_id' => $request->razorpay_order_id,
                'error' => $e->getMessage(),
            ]);

            Alert::error(
                'Payment Verification Failed',
                'We could not verify your payment. Please contact support if money was deducted.'
            );

            return redirect()->route('customer.orders');
        }
    }
}
