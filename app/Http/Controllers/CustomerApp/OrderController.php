<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Order;
use App\Models\OrderShipment;
use App\Http\Resources\OrdersCollection;
use App\Http\Resources\OrderCollection;

class OrderController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');
    }

    /**
     * GET /api/customer/orders
     * List current user's orders (minimal data).
     * Query params:
     *  - per_page (int) default 10
     *  - status (string) e.g. pending|confirmed|shipped|completed|cancelled
     *  - payment_status (string) e.g. pending|paid|failed|refunded
     *  - from, to (YYYY-MM-DD) for created_at date range
     */
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

        $perPage = (int) $request->get('per_page', 10);
        $status = $request->get('status');
        $paymentStatus = $request->get('payment_status');
        $order_type = $request->get('order_type');
        $from = $request->get('from');
        $to = $request->get('to');

        $query = Order::query()
            ->where('customer_id', $user->id)
            ->with([
                'items' => function ($q) {
                    $q->select('id', 'order_id', 'product_variant_id', 'product_title', 'quantity', 'unit_price');
                    $q->with([
                        'variant:id,product_id,image', // eager load variant with image
                        'variant.media:id,product_variant_id,url,thumbnail_url,is_primary,sort_order',
                        'variant.product:id,image',   // fallback to product image if needed
                    ]);
                }
            ])
            ->latest('id');

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($paymentStatus)) {
            $query->where('payment_status', $paymentStatus);
        }
        if (!empty($order_type)) {
            $query->where('order_type', $order_type);
        }

        if (!empty($from)) {
            $query->whereDate('created_at', '>=', Carbon::parse($from)->toDateString());
        }
        if (!empty($to)) {
            $query->whereDate('created_at', '<=', Carbon::parse($to)->toDateString());
        }

        $orders = $query->paginate($perPage)->appends($request->query());

        return new OrdersCollection($orders);
    }

    /**
     * GET /api/customer/orders/{order}
     * Show a single order with full details.
     */
    public function show($orderId)
    {
        $user = auth('sanctum')->user();

        $orders = Order::query()
            ->where('customer_id', $user->id)
            ->where('id', $orderId)
            ->with([
                'items',                    // full order items
                'payments',                 // all payments
                'shipments',                // shipments shell
                'shipments.items',          // pivot entries (order_shipment_items)
            ])
            ->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return new OrderCollection($orders);
    }

    /**
     * PATCH /api/customer/orders/{order}/cancel
     * Allow user to request cancellation for their own order.
     * Rules (typical):
     *  - Only if order.status is 'pending' or 'confirmed'
     *  - And shipments are not yet 'shipped'
     */
    public function cancel($orderId)
    {
        $user = auth('sanctum')->user();

        /** @var Order|null $order */
        $order = Order::where('customer_id', $user->id)->where('id', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Disallow cancel if already shipped/completed/cancelled
        if (in_array($order->status, ['shipped', 'completed', 'cancelled'], true)) {
            return response()->json(['message' => 'Order cannot be cancelled now'], 422);
        }

        // If any shipment is shipped, block cancellation
        $anyShipped = OrderShipment::where('order_id', $order->id)
            ->where('status', 'shipped')
            ->exists();

        if ($anyShipped) {
            return response()->json(['message' => 'Order already shipped'], 422);
        }

        // OO-style update
        $order->status = 'cancelled';
        // If payment was already captured, you may set payment_status to 'refund_initiated'
        if ($order->payment_status === 'paid') {
            // optional business rule
            // $order->payment_status = 'refund_initiated';
        }
        $order->save();

        return response()->json(['message' => 'Order cancelled', 'status' => $order->status]);
    }
}
