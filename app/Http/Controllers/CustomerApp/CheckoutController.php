<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Services\Order\DelhiveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Resources\CartCollection;
use App\Mail\OrderPlacedMail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderShipment;
use App\Models\OrderShipmentItem;
use App\Models\ProductVariant;
use App\Models\Payment;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\CouponUsageHistory;
use App\Models\FreightCharge;
use App\Models\PaymentGateway;
use App\Models\ShippingZone;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');
    }

    public function store(Request $request)
    {
        $user = auth('sanctum')->user();

        $validator = Validator::make($request->all(), [
            'shipping_address_id' => 'required|integer|exists:addresses,id',
            'payment_method' => 'required|string',     // e.g. cod|razorpay|stripe
            'delivery_charges' => 'sometimes|array',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }
        $data = $validator->validated();

        $orderType = $request->order_type ?? 'order';

        // 1) Load & authorize address (belongs to this user)
        $shippingAddress = \App\Models\Address::where('id', $data['shipping_address_id'])
            ->where('customer_id', $user->id)
            ->firstOrFail();

        // Snapshot once; use for both shipping & billing
        $addressSnapshot = $shippingAddress->toSnapshot();
        $deliveryCharges = $data['delivery_charges'] ?? [];

        // 2) Load cart
        $cart = \App\Models\CartItem::with(['variant.product'])
            ->where('user_id', $user->id)->where('type', $orderType)
            ->get();

        if ($cart->isEmpty()) {
            return response()->json(['success' => 1, 'message' => 'Cart is empty']);
        }

        $couponcode = $request->code ?? null;
        // $inStockItems = [];
        // $outOfStockItems = [];

        // foreach ($cart as $line) {
        //     $variant = \App\Models\ProductVariant::select('id', 'stock', 'sku')
        //         ->find($line->product_variant_id);

        //     if (($variant->stock ?? 0) > 0) {
        //         $inStockItems[] = $line;
        //     } else {
        //         $outOfStockItems[] = [
        //             'product_variant_id' => $variant->id,
        //             'sku' => $variant->sku,
        //             'message' => 'Out of stock'
        //         ];
        //     }
        // }

        // // ❌ Mixed case → STOP
        // if (count($inStockItems) > 0 && count($outOfStockItems) > 0) {
        //     return response()->json([
        //         'success' => 0,
        //         'message' => 'Some items are out of stock',
        //         'out_of_stock_items' => $outOfStockItems
        //     ]);
        // }

        // // ✅ Decide order type
        // $orderType = count($outOfStockItems) > 0 ? 'preorder' : 'order';

        $orderType = $request->order_type ?? 'order';

        $inStockItems = [];
        $outOfStockItems = [];

        foreach ($cart as $line) {
            $variant = ProductVariant::select(
                'id',
                'stock',
                'sku',
                'preorder',
                'preorder_stock'
            )->find($line->product_variant_id);

            if ($orderType === 'order') {

                // ✅ Normal order → check stock
                if (($variant->stock ?? 0) >= $line->quantity) {
                    $inStockItems[] = $line;
                } else {
                    $outOfStockItems[] = [
                        'product_variant_id' => $variant->id,
                        'sku' => $variant->sku,
                        'message' => 'Insufficient stock'
                    ];
                }
            } else {

                // ✅ Preorder → check
                if ($variant->preorder) {

                    // Optional: check preorder stock limit
                    if (
                        is_null($variant->preorder_stock) ||
                        $variant->preorder_stock >= $line->quantity
                    ) {
                        $inStockItems[] = $line;
                    } else {
                        $outOfStockItems[] = [
                            'product_variant_id' => $variant->id,
                            'sku' => $variant->sku,
                            'message' => 'Preorder stock limit reached'
                        ];
                    }
                } else {
                    $outOfStockItems[] = [
                        'product_variant_id' => $variant->id,
                        'sku' => $variant->sku,
                        'message' => 'Preorder not available'
                    ];
                }
            }
        }

        // ❌ Mixed → stop
        if (count($inStockItems) > 0 && count($outOfStockItems) > 0) {
            return response()->json([
                'success' => 0,
                'message' => 'Some items are not available for selected order type',
                'out_of_stock_items' => $outOfStockItems
            ]);
        }

        // ❌ All invalid → stop
        if (count($inStockItems) === 0) {
            return response()->json([
                'success' => 0,
                'message' => $orderType === 'order'
                    ? 'All items are out of stock'
                    : 'No items available for preorder',
                'out_of_stock_items' => $outOfStockItems
            ]);
        }

        // ✅ Only proceed with valid items
        $cart = collect($inStockItems);

        return \DB::transaction(function () use ($couponcode, $shippingAddress, $user, $data, $deliveryCharges, $cart, $addressSnapshot, $orderType) {

            // ---- Prepare lines & subtotal
            $subtotal = 0.00;
            $itemsPrepared = [];

            foreach ($cart as $line) {
                $variant = ProductVariant::with('product')
                    ->select('id', 'price', 'stock', 'product_id', 'sku', 'weight')
                    ->findOrFail($line->product_variant_id);

                // if ($orderType == 'order' && (int)$variant->stock < (int)$line->quantity) {
                //     abort(422, "Insufficient stock for SKU {$variant->sku}");
                // }

                $unitPrice = (float) $variant->price;
                $lineTotal = $unitPrice * (int) $line->quantity;
                $subtotal += $lineTotal;
                $weight = (float) $variant->weight;

                $itemsPrepared[] = [
                    'variant' => $variant,
                    'seller_id' => (int) ($variant->product?->seller_id ?? 0),
                    'unit_price' => $unitPrice,
                    'quantity' => (int) $line->quantity,
                    'subtotal' => $lineTotal,
                    'title' => (string) ($variant->product?->title ?? ''),
                    'sku' => (string) $variant->sku,
                    'weight' => (float) $weight,
                ];
            }

            $discount = 0;
            if (!empty($couponcode)) {

                $coupon = Coupon::where('code', $couponcode)
                    ->where('status', 'active')
                    ->first();

                if (
                    $coupon &&
                    (!$coupon->expiry_date || Carbon::parse($coupon->expiry_date)->endOfDay()->isFuture()) &&
                    (!$coupon->minimum_purchase || $subtotal >= $coupon->minimum_purchase)
                ) {
                    if ($coupon->type === 'fixed') {
                        $discount = min($coupon->discount, $subtotal);
                    } else {
                        $discount = round(($subtotal * $coupon->discount) / 100);
                    }
                }
            }

            // ---- Create Order (OO)
            $invoiceId = 'INV-' . now()->format('Ymd-His');
            // $invoiceId = 'INV-' . now()->format('Ymd-His') . '-' . \Str::upper(\Str::random(6));

            $order = new Order();
            $order->customer_id = $user->id;
            $order->status = 'pending';
            $order->subtotal = $subtotal;
            $order->tax_total = 0.00;
            $order->discount_total = $discount;
            $order->delivery_total = 0.00; // after shipments
            $order->grand_total = 0.00; // after shipments
            $order->invoice_id = $invoiceId;
            $order->payment_method = $data['payment_method'];
            $order->payment_status = 'pending';
            $order->shipping_address = $addressSnapshot; // same snapshot for both
            $order->billing_address = $addressSnapshot;
            $order->order_type = $orderType;
            $order->save();
            if ($order->order_type == 'preorder') {
                $invoiceId = 'SSHP' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
            } else {
                $invoiceId = 'SSH' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
            }
            $order->invoice_id = $invoiceId;
            $order->save();
            if ($order->payment_method == 'cod') {
                if ($discount > 0) {
                    $history = new CouponUsageHistory();
                    $history->coupon_id = $coupon->id;
                    $history->user_id = $user->id;
                    $history->order_id = $order->id;
                    $history->discount_amount = $discount;
                    $history->used_at = Carbon::now();
                    $history->save();
                }
            }

            // ---- Order Items + stock
            $orderItems = [];
            $totalWeight = 0;

            foreach ($itemsPrepared as $row) {
                $oi = new OrderItem();
                $oi->order_id = $order->id;
                $oi->product_variant_id = $row['variant']->id;
                $oi->seller_id = $row['seller_id'];
                $oi->product_title = $row['title'];
                $oi->sku = $row['sku'];
                $oi->unit_price = $row['unit_price'];
                $oi->quantity = $row['quantity'];
                $oi->subtotal = $row['subtotal'];
                $oi->weight = $row['weight'];
                $oi->save();

                if ($orderType == 'order') {
                    $row['variant']->stock = (int) $row['variant']->stock - (int) $row['quantity'];
                    $row['variant']->save();
                }

                $orderItems[] = $oi;

                $totalWeight += $row['weight'] * $row['quantity'];
            }

            $weightInGrams = $totalWeight * 1000;

            $shipping = 0;
            // $delhivery = new DelhiveryService();
            // if ($shippingAddress && !empty($shippingAddress->pincode)) {

            //     $shippingResponse = $delhivery->calculateShipping([
            //         'origin_pin'      => '572107', // your warehouse
            //         'destination_pin' => $shippingAddress->pincode,
            //         'weight'          => $weightInGrams,
            //         'payment_type'    => 'Pre-paid',
            //     ]);

            //     if ($shippingResponse['success']) {
            //         $shipping = $shippingResponse['data'][0]['total_amount'] ?? 0;
            //     }
            // }

            $zone = ShippingZone::whereJsonContains('regions', $shippingAddress->state)->first();
            if ($zone) {

                // Free shipping check
                if ($zone->free_shipping == 'yes') {
                    $shipping = 0;
                } else {

                    $charge = FreightCharge::where('shipping_zone_id', $zone->id)
                        ->where('min_weight', '<=', $totalWeight)
                        ->where('max_weight', '>=', $totalWeight)
                        ->first();

                    if ($charge) {
                        $shipping = $charge->charge;
                    } else {
                        // Optional fallback (no slab found)
                        $shipping = 0;
                    }
                    $latestCharge = FreightCharge::where('shipping_zone_id', $zone->id)->latest()->first();
                    if ($latestCharge->max_weight < $totalWeight) {
                        $shipping = (float) $latestCharge->charge + 30;
                    }
                }
            }

            $shipping = round($shipping);


            // ---- Shipments per seller (billing=shipping same snapshot)
            $bySeller = collect($orderItems)->groupBy(fn($it) => (int) $it->seller_id);
            $deliveryTotal = $shipping;

            $grand = (float) $order->subtotal
                + (float) $order->tax_total
                - (float) $order->discount_total
                + (float) $deliveryTotal;

            $order->delivery_total = $deliveryTotal;
            $order->grand_total = $grand;
            $order->save();

            if ($order->payment_method == 'razorpay') {

                $key = 'rzp_test_R9GdWcNAde0fOH';
                $secret = 'EfDOgPQMM170Rv6ENjAaqsyM';

                // $key = 'rzp_test_R9GdWcNAde0fOH';
                // $secret = 'EfDOgPQMM170Rv6ENjAaqsyM';

                $api = new Api($key, $secret);

                $pamount = $order->grand_total * 100;
                $razorpayResponse = $api->order->create(array(

                    'receipt' => "$invoiceId",
                    'amount' => $pamount,
                    'currency' => 'INR',
                    'notes' => array('key1' => 'value3', 'key2' => 'value2')
                ));
            }

            // ---- Payment row
            $payment = new \App\Models\Payment();
            $payment->order_id = $order->id;
            $payment->user_id = $user->id;
            $payment->amount = $order->grand_total;
            $payment->currency = 'INR';
            $payment->status = 'pending';
            $payment->method = $order->payment_method;
            $payment->provider = in_array($order->payment_method, ['razorpay', 'stripe', 'phonepe']) ? $order->payment_method : null;
            $payment->provider_order_id = $razorpayResponse['id'] ?? null;
            $payment->reference_no = $order->invoice_id;
            $payment->save();

            // ---- Clear cart
            // $toDelete = \App\Models\CartItem::where('user_id', $user->id)->get();
            // foreach ($toDelete as $ci) {
            //     $ci->delete();
            // }

            return response()->json([
                'success' => 1,
                'message' => 'Order placed',
                'order_id' => $order->id,
                'invoice_id' => $order->invoice_id,
                'payment_id' => $payment->id,
                'payment_status' => $order->payment_status,
                'grand_total' => $order->grand_total,
                'delivery_total' => $order->delivery_total,
                'address' => $order->shipping_address, // same as billing
                'provider_order_id' => $payment->provider_order_id
            ]);
        });
    }

    public function show(Request $request)
    {
        $user = auth('sanctum')->user();
        $type = $request->order_type ?? 'order';
        $cartItems = CartItem::with([
            'variant.product:id,title,slug,category_id,image,description,short_description',
            'variant.media',
            'variant.attributeValues.attribute',
        ])->where('user_id', $user->id)->where('type', $type)->get();

        if (count($cartItems) == 0) {
            return response()->json([
                'success' => 0,
                'message' => 'Your Cart Is Empty'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|integer|exists:addresses,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }
        $inStockItems = [];
        $outOfStockItems = [];

        foreach ($cartItems as $line) {
            $variant = ProductVariant::select(
                'id',
                'stock',
                'sku',
                'preorder',
                'preorder_stock'
            )->find($line->product_variant_id);

            if ($type === 'order') {

                // ✅ Normal order → check stock
                if (($variant->stock ?? 0) >= $line->quantity) {
                    $inStockItems[] = $line;
                } else {
                    $outOfStockItems[] = [
                        'product_variant_id' => $variant->id,
                        'sku' => $variant->sku,
                        'message' => 'Insufficient stock'
                    ];
                }
            } else {

                // ✅ Preorder → check
                if ($variant->preorder) {

                    // Optional: check preorder stock limit
                    if (
                        is_null($variant->preorder_stock) ||
                        $variant->preorder_stock >= $line->quantity
                    ) {
                        $inStockItems[] = $line;
                    } else {
                        $outOfStockItems[] = [
                            'product_variant_id' => $variant->id,
                            'sku' => $variant->sku,
                            'message' => 'Preorder stock limit reached'
                        ];
                    }
                } else {
                    $outOfStockItems[] = [
                        'product_variant_id' => $variant->id,
                        'sku' => $variant->sku,
                        'message' => 'Preorder not available'
                    ];
                }
            }
        }

        // ❌ Mixed case → block
        if (count($inStockItems) > 0 && count($outOfStockItems) > 0) {
            return response()->json([
                'success' => 0,
                'type' => 'mixed',
                'message' => 'Some items are not available for selected order type',
                'out_of_stock_items' => $outOfStockItems
            ]);
        }

        // ❌ All invalid
        if (count($inStockItems) === 0) {
            return response()->json([
                'success' => 0,
                'type' => 'invalid',
                'message' => $type === 'order'
                    ? 'All items are out of stock'
                    : 'No items available for preorder',
                'out_of_stock_items' => $outOfStockItems
            ]);
        }

        // ✅ Only valid items
        $cart = new CartCollection(collect($inStockItems));

        // Addresses
        $addresses = Address::where('customer_id', $user->id)
            ->orderByDesc('default')->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'name' => $a->name,
                'mobile' => $a->mobile,
                'email' => $a->email,
                'address1' => $a->address,
                'address2' => $a->address_2,
                'city' => $a->city,
                'state' => $a->state ?? $a->state_id,
                'pincode' => $a->pincode,
                'default' => (bool) $a->default,
            ]);

        // Shipping options
        $shippingOptions = [
            ['code' => 'std', 'label' => 'Standard (3–5 days)', 'charge' => 50, 'eta_days' => '3-5'],
        ];

        $gateways = PaymentGateway::where('status', 'show')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($g) {
                // normalize config whether it's cast array or raw json string
                $config = is_array($g->config)
                    ? $g->config
                    : (is_string($g->config) ? (json_decode($g->config, true) ?: []) : []);

                return [
                    'code' => $g->code,
                    'name' => $g->name,
                    'image' => $g->image ? asset($g->image) : null,
                    'description' => $g->description,
                    'is_online' => $g->is_online === 'yes',
                    'fee_percent' => (float) $g->fee_percent,
                    'fee_fixed' => (float) $g->fee_fixed,
                    'sort_order' => (int) $g->sort_order,
                    'config' => $config, // ← no json_decode() here
                ];
            })->values();


        // Summary
        if ($type == 'preorder') {
            $cartArray = $cart->toArray($request);
            $subtotal = (float) ($cartArray['preorder_totals']['subtotal'] ?? 0);
            $actualtotal = (float) ($cartArray['preorder_totals']['actualtotal'] ?? 0);
            $shipping = 0;
            $discount = 0;
            $tax = 0;
            $items = (int) ($cartArray['preorder_totals']['items'] ?? 0);
        } else {
            $cartArray = $cart->toArray($request);
            $subtotal = (float) ($cartArray['totals']['subtotal'] ?? 0);
            $actualtotal = (float) ($cartArray['totals']['actualtotal'] ?? 0);
            $shipping = 0;
            $discount = 0;
            $tax = 0;
            $items = (int) ($cartArray['totals']['items'] ?? 0);
        }

        // ✅ Calculate weight
        $totalWeight = 0;

        foreach ($cartItems as $item) {
            $variant = $item->variant;

            $weight = $variant->weight ?? 0; // assume KG
            $qty = $item->quantity ?? 1;

            $totalWeight += $weight * $qty;
        }

        // convert to grams
        $weightInGrams = $totalWeight * 1000;

        // ✅ Get default address
        $defaultAddress = Address::where('customer_id', $user->id)->where('id', $request->address_id)->first();
        $shipping = 0;
        $zone = ShippingZone::whereJsonContains('regions', $defaultAddress->state)->first();
        if ($zone) {
            // Free shipping check
            if ($zone->free_shipping == 'yes') {
                $shipping = 0;
            } else {

                $charge = FreightCharge::where('shipping_zone_id', $zone->id)
                    ->where('min_weight', '<=', $totalWeight)
                    ->where('max_weight', '>=', $totalWeight)
                    ->first();
                if ($charge) {
                    $shipping = $charge->charge;
                } else {
                    // Optional fallback (no slab found)
                    $shipping = 0;
                }
                $latestCharge = FreightCharge::where('shipping_zone_id', $zone->id)->latest()->first();
                if ($latestCharge->max_weight < $totalWeight) {
                    $shipping = (float) $latestCharge->charge + 30;
                }
            }
        }
        $shipping = round($shipping);
        $appliedCoupon = null;

        if ($request->filled('code')) {

            $coupon = Coupon::where('code', $request->code)
                ->where('status', 'active')
                ->first();
            // dd($coupon);
            if (
                $coupon &&
                (!$coupon->expiry_date || Carbon::parse($coupon->expiry_date)->endOfDay()->isFuture()) &&
                (!$coupon->minimum_purchase || $subtotal >= $coupon->minimum_purchase)
            ) {
                if ($coupon->type === 'fixed') {
                    $discount = min($coupon->discount, $subtotal);
                } else {
                    $discount = round(($subtotal * $coupon->discount) / 100);
                }

                $appliedCoupon = [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->discount,
                    'discount' => $discount,
                ];
            }
        }

        $grandTotal = max(0, $subtotal + $shipping + $tax - $discount);

        return response()->json([
            'success' => 1,
            'cart' => $cartArray,
            'addresses' => $addresses,
            'payment_gateways' => $gateways,   // ← includes config
            'shipping_options' => $shippingOptions,
            'summary' => [
                'items' => $items,
                'subtotal' => $subtotal,
                'actualtotal' => $actualtotal,
                'shipping' => $shipping,
                'discount' => $discount,
                'tax' => $tax,
                'grand_total' => $grandTotal,
            ],
            'coupon' => $appliedCoupon,
            'flags' => [
                'allow_coupon' => true,
            ],
        ]);
    }
}
