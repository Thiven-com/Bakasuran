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
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use RealRashid\SweetAlert\Facades\Alert;

class OrderController extends Controller
{
    /**
     * ORDER NOW: Save the selected product in the session
     * and redirect directly to checkout.
     */

public function buyNow(Request $request)
{
    $validated = $request->validate([
        'product_variant_id' => [
            'required',
            'integer',
            'exists:product_variants,id',
        ],
        'quantity' => ['required', 'integer', 'min:1'],
    ]);

    if (!Auth::guard('customer')->check()) {
        return redirect()->route('login');
    }

    $variant = ProductVariant::with('product')
        ->findOrFail($validated['product_variant_id']);

    $quantity = (int) $validated['quantity'];

    \Log::info('Buy Now variant check', [
        'variant_id' => $variant->id,
        'variant_status' => $variant->status,
        'stock' => $variant->stock,
        'quantity' => $quantity,
        'has_product' => $variant->product !== null,
    ]);

    if (!$variant->product || $variant->status !== 'show') {
    return back()->with(
        'error',
        'This product is not available.'
    );
}

    if ($quantity > (int) $variant->stock) {
        return back()->with(
            'error',
            'Requested quantity is unavailable.'
        );
    }

    if (
        $variant->product_min_order &&
        $quantity < (int) $variant->product_min_order
    ) {
        return back()->with(
            'error',
            'Minimum order quantity is ' . $variant->product_min_order
        );
    }

    if (
        $variant->product_max_order &&
        $quantity > (int) $variant->product_max_order
    ) {
        return back()->with(
            'error',
            'Maximum order quantity is ' . $variant->product_max_order
        );
    }

    session([
        'buy_now' => [
            'product_variant_id' => $variant->id,
            'quantity' => $quantity,
        ],
    ]);

    \Log::info('Buy Now session saved', [
        'buy_now' => session('buy_now'),
    ]);

    return redirect()->route('checkout', [
        'mode' => 'buy_now',
    ]);
}


    /**
     * Create an order from ORDER NOW or the shopping cart.
     */
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => ['required', 'integer'],
            'payment_method' => ['required', 'in:cod,online_payment'],
            'mode' => ['nullable', 'in:buy_now'],
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

        $address = Address::where('id', $request->address_id)
            ->where('customer_id', $user->id)
            ->first();

        if (!$address) {
            Alert::toast('Please select a valid delivery address.', 'error');
            return back()->withInput();
        }

        $isBuyNow = $request->input('mode') === 'buy_now';
        $buyNow = $isBuyNow ? session('buy_now') : null;

        if (
            $isBuyNow &&
            (
                !$buyNow ||
                empty($buyNow['product_variant_id']) ||
                !isset($buyNow['quantity']) ||
                (int) $buyNow['quantity'] < 1
            )
        ) {
            Alert::toast('Your direct purchase session has expired.', 'warning');

            return redirect()->route('checkout');
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

        try {
            $result = DB::transaction(function () use (
                $request,
                $user,
                $shippingAddress,
                $isBuyNow,
                $buyNow
            ) {
                $items = collect();

                if ($isBuyNow) {
                    $variant = ProductVariant::with('product')
                        ->whereKey($buyNow['product_variant_id'])
                        ->lockForUpdate()
                        ->first();

                    $quantity = (int) $buyNow['quantity'];

                    if (!$variant || !$variant->product) {
                        throw new \RuntimeException(
                            'The selected product is no longer available.'
                        );
                    }

                    $items->push((object) [
                        'product_variant_id' => $variant->id,
                        'variant' => $variant,
                        'quantity' => $quantity,
                        'unit_price' => $variant->price,
                    ]);
                } else {
                    $cartItems = CartItem::where('user_id', $user->id)
                        ->get();

                    if ($cartItems->isEmpty()) {
                        throw new \RuntimeException('Your cart is empty.');
                    }

                    foreach ($cartItems as $cartItem) {
                        $variant = ProductVariant::with('product')
                            ->whereKey($cartItem->product_variant_id)
                            ->lockForUpdate()
                            ->first();

                        if (!$variant || !$variant->product) {
                            throw new \RuntimeException(
                                'A product in your cart is no longer available.'
                            );
                        }

                        $items->push((object) [
                            'product_variant_id' => $variant->id,
                            'variant' => $variant,
                            'quantity' => (int) $cartItem->quantity,
                            'unit_price' => $variant->price,
                        ]);
                    }
                }

                // Validate every item before creating the order.
                foreach ($items as $item) {
                    $variant = $item->variant;
                    $quantity = (int) $item->quantity;

                    if ($variant->status !== 'active') {
                        throw new \RuntimeException(
                            $variant->product->title . ' is not available.'
                        );
                    }

                    if ($quantity < 1 || $quantity > (int) $variant->stock) {
                        throw new \RuntimeException(
                            'Insufficient stock for ' . $variant->product->title
                        );
                    }

                    if (
                        $variant->product_min_order &&
                        $quantity < (int) $variant->product_min_order
                    ) {
                        throw new \RuntimeException(
                            'Minimum order quantity is ' .
                            $variant->product_min_order
                        );
                    }

                    if (
                        $variant->product_max_order &&
                        $quantity > (int) $variant->product_max_order
                    ) {
                        throw new \RuntimeException(
                            'Maximum order quantity is ' .
                            $variant->product_max_order
                        );
                    }
                }

                $subtotal = $items->sum(function ($item) {
                    return (float) $item->unit_price * $item->quantity;
                });

                // TODO: Connect the checkout shipping and coupon calculations.
                $shipping = 0;
                $discount = 0;
                $grandTotal = max(0, $subtotal + $shipping - $discount);

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

                foreach ($items as $item) {
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

                if ($request->payment_method === 'cod') {
                    foreach ($items as $item) {
                        $updated = ProductVariant::whereKey(
                            $item->product_variant_id
                        )
                            ->where('stock', '>=', $item->quantity)
                            ->decrement('stock', $item->quantity);

                        if (!$updated) {
                            throw new \RuntimeException(
                                'Stock changed while placing your order. Please try again.'
                            );
                        }
                    }

                    $order->update([
                        'status' => 'placed',
                        'payment_status' => 'pending',
                    ]);

                    if (!$isBuyNow) {
                        CartItem::where('user_id', $user->id)->delete();
                    }

                    return [
                        'type' => 'cod',
                        'order_id' => $order->id,
                    ];
                }

                $gateway = PaymentGateway::where('code', 'razorpay')
                    ->where('is_online', 1)
                    ->where('status', 1)
                    ->first();

                if (!$gateway) {
                    throw new \RuntimeException(
                        'Razorpay is not configured. Please select Cash on Delivery.'
                    );
                }

                $config = is_array($gateway->config)
                    ? $gateway->config
                    : json_decode($gateway->config ?? '{}', true);

                $key = $config['key_id'] ?? null;
                $secret = $config['secret'] ?? null;

                if (!$key || !$secret) {
                    throw new \RuntimeException(
                        'Razorpay keys are missing. Please select Cash on Delivery.'
                    );
                }

                $api = new Api($key, $secret);

                $razorpayOrder = $api->order->create([
                    'receipt' => $invoiceId,
                    'amount' => (int) round($grandTotal * 100),
                    'currency' => 'INR',
                ]);

                $payment->update([
                    'provider' => 'razorpay',
                    'provider_order_id' => $razorpayOrder['id'],
                ]);

                return [
                    'type' => 'online',
                    'order' => $order,
                    'payment' => $payment,
                    'razorpay_order' => $razorpayOrder,
                    'grand_total' => $grandTotal,
                    'user' => $user,
                    'key' => $key,
                ];
            });

            if ($result['type'] === 'cod') {
                if ($isBuyNow) {
                    session()->forget('buy_now');
                }

                Alert::success('Success', 'Your order has been placed.');

                return redirect()->route('customer.orders');
            }

            return view('website.razorpay-payment', [
                'order' => $result['order'],
                'payment' => $result['payment'],
                'razorpayOrder' => $result['razorpay_order'],
                'grandTotal' => $result['grand_total'],
                'user' => $result['user'],
                'razorpayKey' => $result['key'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Checkout order creation failed', [
                'customer_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            Alert::toast(
                $e instanceof \RuntimeException
                    ? $e->getMessage()
                    : 'Unable to place your order. Please try again.',
                'error'
            );

            return back()->withInput();
        }
    }

    /**
     * Display the logged-in customer's orders.
     */
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

    /**
     * Display one order belonging to the logged-in customer.
     */
    public function show($id)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login');
        }

        $order = Order::where(
            'customer_id',
            Auth::guard('customer')->id()
        )
            ->where('id', $id)
            ->firstOrFail();

        return view('website.order-details', compact('order'));
    }

    /**
     * Verify Razorpay payment and finalize the order.
     */
    public function paymentSuccess(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
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
                : json_decode($gateway->config ?? '{}', true);

            $key = $config['key_id'] ?? null;
            $secret = $config['secret'] ?? null;

            if (!$key || !$secret) {
                throw new \RuntimeException(
                    'Razorpay credentials are missing.'
                );
            }

            $api = new Api($key, $secret);

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);

            DB::transaction(function () use ($payment, $request) {
                $lockedPayment = Payment::whereKey($payment->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedPayment->status !== 'pending') {
                    throw new \RuntimeException(
                        'This payment has already been processed.'
                    );
                }

                $order = Order::where('id', $lockedPayment->order_id)
                    ->where('customer_id', $lockedPayment->user_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $orderItems = OrderItem::where('order_id', $order->id)->get();

                if ($orderItems->isEmpty()) {
                    throw new \RuntimeException(
                        'No items were found for this order.'
                    );
                }

                foreach ($orderItems as $item) {
                    $variant = ProductVariant::whereKey(
                        $item->product_variant_id
                    )
                        ->lockForUpdate()
                        ->first();

                    if (!$variant || (int) $variant->stock < (int) $item->quantity) {
                        throw new \RuntimeException(
                            'Insufficient stock to complete the order.'
                        );
                    }
                }

                foreach ($orderItems as $item) {
                    $updated = ProductVariant::whereKey(
                        $item->product_variant_id
                    )
                        ->where('stock', '>=', $item->quantity)
                        ->decrement('stock', $item->quantity);

                    if (!$updated) {
                        throw new \RuntimeException(
                            'Stock changed while completing the order.'
                        );
                    }
                }

                $lockedPayment->update([
                    'provider_payment_id' => $request->razorpay_payment_id,
                    'provider_signature' => $request->razorpay_signature,
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                $order->update([
                    'status' => 'placed',
                    'payment_status' => 'paid',
                ]);

                // Clear cart only if it was a cart checkout.
                // Direct orders must preserve the existing cart.
                if (!session()->has('buy_now')) {
                    CartItem::where('user_id', $lockedPayment->user_id)
                        ->delete();
                }
            });

            session()->forget('buy_now');

            Alert::success(
                'Payment Successful',
                'Your order has been placed.'
            );

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
