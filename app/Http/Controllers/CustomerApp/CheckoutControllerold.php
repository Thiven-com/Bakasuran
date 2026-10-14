<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
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
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;

class CheckoutControllerold extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');
    }

    public function store(Request $request)
    {
        $user = auth('sanctum')->user();

        // $data = $request->validate([
        //     'shipping_address_id' => 'required|integer|exists:addresses,id',
        //     'payment_method'      =  > 'required|string',     // e.g. cod|razorpay|stripe
        //     'delivery_charges'    => 'sometimes|array',     // {seller_id: charge}
        // ]);
        $validator = Validator::make($request->all(), [
            'shipping_address_id' => 'required|integer|exists:addresses,id',
            'payment_method'      => 'required|string',     // e.g. cod|razorpay|stripe
            'delivery_charges'    => 'sometimes|array',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }
        $data = $validator->validated();
        // 1) Load & authorize address (belongs to this user)
        $shipping = \App\Models\Address::where('id', $data['shipping_address_id'])
            ->where('customer_id', $user->id)
            ->firstOrFail();

        // Snapshot once; use for both shipping & billing
        $addressSnapshot = $shipping->toSnapshot();
        $deliveryCharges = $data['delivery_charges'] ?? [];

        // 2) Load cart
        $cart = \App\Models\CartItem::with(['variant.product'])
            ->where('user_id', $user->id)
            ->get();

        if ($cart->isEmpty()) {
            return response()->json(['success' => 1, 'message' => 'Cart is empty'], 422);
        }
        $inStockItems = [];
        $outOfStockItems = [];

        foreach ($cart as $line) {
            $variant = \App\Models\ProductVariant::select('id', 'stock', 'sku')
                ->find($line->product_variant_id);

            if (($variant->stock ?? 0) > 0) {
                $inStockItems[] = $line;
            } else {
                $outOfStockItems[] = [
                    'product_variant_id' => $variant->id,
                    'sku' => $variant->sku,
                    'message' => 'Out of stock'
                ];
            }
        }

        // ❌ Mixed case → STOP
        if (count($inStockItems) > 0 && count($outOfStockItems) > 0) {
            return response()->json([
                'success' => 0,
                'message' => 'Some items are out of stock',
                'out_of_stock_items' => $outOfStockItems
            ]);
        }

        // ✅ Decide order type
        $orderType = count($outOfStockItems) > 0 ? 'preorder' : 'order';

        return \DB::transaction(function () use ($user, $data, $deliveryCharges, $cart, $addressSnapshot, $orderType) {

            // ---- Prepare lines & subtotal
            $subtotal = 0.00;
            $itemsPrepared = [];

            foreach ($cart as $line) {
                $variant = \App\Models\ProductVariant::with('product')
                    ->select('id', 'price', 'stock', 'product_id', 'sku', 'weight')
                    ->findOrFail($line->product_variant_id);

                // if ($orderType == 'order' && (int)$variant->stock < (int)$line->quantity) {
                //     abort(422, "Insufficient stock for SKU {$variant->sku}");
                // }

                $unitPrice = (float)$variant->price;
                $lineTotal = $unitPrice * (int)$line->quantity;
                $subtotal += $lineTotal;
                $weight = (float)$variant->weight;

                $itemsPrepared[] = [
                    'variant'   => $variant,
                    'seller_id' => (int)($variant->product?->seller_id ?? 0),
                    'unit_price' => $unitPrice,
                    'quantity'  => (int)$line->quantity,
                    'subtotal'  => $lineTotal,
                    'title'     => (string)($variant->product?->title ?? ''),
                    'sku'       => (string)$variant->sku,
                    'weight'       => (float)$weight,
                ];
            }

            // ---- Create Order (OO)
            $invoiceId = 'INV-' . now()->format('Ymd-His');
            // $invoiceId = 'INV-' . now()->format('Ymd-His') . '-' . \Str::upper(\Str::random(6));

            $order = new \App\Models\Order();
            $order->customer_id          = $user->id;
            $order->status           = 'pending';
            $order->subtotal         = $subtotal;
            $order->tax_total        = 0.00;
            $order->discount_total   = 0.00;
            $order->delivery_total   = 0.00; // after shipments
            $order->grand_total      = 0.00; // after shipments
            $order->invoice_id       = $invoiceId;
            $order->payment_method   = $data['payment_method'];
            $order->payment_status   = 'pending';
            $order->shipping_address = $addressSnapshot; // same snapshot for both
            $order->billing_address  = $addressSnapshot;
            $order->order_type = $orderType;
            $order->save();

            // ---- Order Items + stock
            $orderItems = [];
            foreach ($itemsPrepared as $row) {
                $oi = new \App\Models\OrderItem();
                $oi->order_id            = $order->id;
                $oi->product_variant_id  = $row['variant']->id;
                $oi->seller_id           = $row['seller_id'];
                $oi->product_title       = $row['title'];
                $oi->sku                 = $row['sku'];
                $oi->unit_price          = $row['unit_price'];
                $oi->quantity            = $row['quantity'];
                $oi->subtotal            = $row['subtotal'];
                $oi->weight              = $row['weight'];
                $oi->save();

                if ($orderType == 'order') {
                    $row['variant']->stock = (int)$row['variant']->stock - (int)$row['quantity'];
                    $row['variant']->save();
                }

                $orderItems[] = $oi;
            }

            // ---- Shipments per seller (billing=shipping same snapshot)
            $bySeller = collect($orderItems)->groupBy(fn($it) => (int)$it->seller_id);
            $deliveryTotal = 0.00;

            // foreach ($bySeller as $sellerId => $items) {
            //     $charge = (float) ($deliveryCharges[$sellerId] ?? 0.00);
            //     $deliveryTotal += $charge;

            //     $shipment = new \App\Models\OrderShipment();
            //     $shipment->order_id         = $order->id;
            //     $shipment->seller_id        = (int)$sellerId;
            //     $shipment->shipment_id      = 'SHP-' . now()->format('Ymd') . '-' . \Str::upper(\Str::random(5));
            //     $shipment->carrier          = null;
            //     $shipment->tracking_no      = null;
            //     $shipment->delivery_charge  = $charge;
            //     $shipment->status           = 'pending';
            //     $shipment->shipping_address = $addressSnapshot; // same as order-level
            //     $shipment->save();

            //     foreach ($items as $it) {
            //         $link = new \App\Models\OrderShipmentItem();
            //         $link->order_shipment_id = $shipment->id;
            //         $link->order_item_id     = $it->id;
            //         $link->save();
            //     }
            // }

            // ---- Totals


            $grand = (float)$order->subtotal
                + (float)$order->tax_total
                - (float)$order->discount_total
                + (float)$deliveryTotal;

            $order->delivery_total = $deliveryTotal;
            $order->grand_total    = $grand;
            $order->save();

            if ($order->payment_method == 'razorpay') {

                $key = 'rzp_test_R9GdWcNAde0fOH';
                $secret = 'EfDOgPQMM170Rv6ENjAaqsyM';

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
            $payment->order_id     = $order->id;
            $payment->user_id      = $user->id;
            $payment->amount       = $order->grand_total;
            $payment->currency     = 'INR';
            $payment->status       = 'pending';
            $payment->method       = $order->payment_method;
            $payment->provider     = in_array($order->payment_method, ['razorpay', 'stripe', 'phonepe']) ? $order->payment_method : null;
            $payment->provider_order_id = $razorpayResponse['id'] ?? null;
            $payment->reference_no = $order->invoice_id;
            $payment->save();

            // ---- Clear cart
            $toDelete = \App\Models\CartItem::where('user_id', $user->id)->get();
            foreach ($toDelete as $ci) {
                $ci->delete();
            }
            try {
                Log::error('Order Details: ' . $order);

                Mail::to($user->email)
                    ->send(new OrderPlacedMail($order));
            } catch (\Exception $e) {
                Log::error('Mail failed: ' . $e->getMessage());
            }
            return response()->json([
                'success' => 1,
                'message'        => 'Order placed',
                'order_id'       => $order->id,
                'invoice_id'     => $order->invoice_id,
                'payment_id'     => $payment->id,
                'payment_status' => $order->payment_status,
                'grand_total'    => $order->grand_total,
                'delivery_total' => $order->delivery_total,
                'address'        => $order->shipping_address, // same as billing
                'provider_order_id' => $payment->provider_order_id
            ], 201);
        });
    }

    public function show(Request $request)
    {
        $user = auth('sanctum')->user();

        // Cart
        // $cartItems = CartItem::with([
        //     'variant.product:id,title,slug,category_id,image,description,short_description',
        //     'variant.media',
        //     'variant.attributeValues.attribute',
        // ])->where('user_id', $user->id)->get();

        // $cart = new CartCollection($cartItems);
        $cartItems = CartItem::with([
            'variant.product:id,title,slug,category_id,image,description,short_description',
            'variant.media',
            'variant.attributeValues.attribute',
        ])->where('user_id', $user->id)->get();

        $inStockItems = [];
        $outOfStockItems = [];

        foreach ($cartItems as $item) {
            if (($item->variant->stock ?? 0) > 0) {
                $inStockItems[] = $item;
            } else {
                $outOfStockItems[] = $item;
            }
        }

        // ✅ Case 1: All items in stock
        if (count($outOfStockItems) === 0) {
            $cart = new CartCollection($cartItems);
            $type = 'order';
        }

        // ✅ Case 2: All items out of stock
        elseif (count($inStockItems) === 0) {
            $cart = new CartCollection($cartItems);
            $type = 'preorder';
        }

        // ❌ Case 3: Mixed
        else {
            return response()->json([
                'success' => 0,
                'type' => 'mixed',
                'message' => 'Some items are out of stock',
                'out_of_stock_items' => new CartCollection(collect($outOfStockItems))
            ]);
        }
        // Addresses
        $addresses = Address::where('customer_id', $user->id)
            ->orderByDesc('default')->get()
            ->map(fn($a) => [
                'id'        => $a->id,
                'name'      => $a->name,
                'mobile'    => $a->mobile,
                'email'     => $a->email,
                'address1'  => $a->address,
                'address2'  => $a->address_2,
                'city'      => $a->city,
                'state'     => $a->state ?? $a->state_id,
                'pincode'   => $a->pincode,
                'default'   => (bool) $a->default,
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
                    'code'        => $g->code,
                    'name'        => $g->name,
                    'image'       => $g->image ? asset($g->image) : null,
                    'description' => $g->description,
                    'is_online'   => $g->is_online === 'yes',
                    'fee_percent' => (float) $g->fee_percent,
                    'fee_fixed'   => (float) $g->fee_fixed,
                    'sort_order'  => (int) $g->sort_order,
                    'config'      => $config, // ← no json_decode() here
                ];
            })->values();


        // Summary
        $cartArray  = $cart->toArray($request);
        $subtotal   = (float)($cartArray['totals']['subtotal'] ?? 0);
        $shipping   = 0;
        $discount   = 0;
        $tax        = 0;

        $grandTotal = max(0, $subtotal + $shipping + $tax - $discount);

        return response()->json([
            'success'          => 1,
            'cart'             => $cartArray,
            'addresses'        => $addresses,
            'payment_gateways' => $gateways,   // ← includes config
            'shipping_options' => $shippingOptions,
            'summary'          => [
                'items'       => (int)($cartArray['totals']['items'] ?? 0),
                'subtotal'    => $subtotal,
                'shipping'    => $shipping,
                'discount'    => $discount,
                'tax'         => $tax,
                'grand_total' => $grandTotal,
            ],
            'flags' => [
                'allow_coupon' => true,
            ],
        ]);
    }
}
