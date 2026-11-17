<?php

namespace App\Http\Controllers\DeliveryApp;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /*
   |--------------------------------------------------------------------------
   | New Orders
   |--------------------------------------------------------------------------
   */

    public function newOrders(Request $request)
    {
        $partner = $request->user();

        $orders = Order::with([
            'items.variant.product.restaurant'
        ])
            ->whereNull('delivery_partner_id')
            ->whereNotIn('status', ['cancelled', 'delivered'])
            ->latest()
            ->get();

        $data = $orders->map(function ($order) use ($partner) {

            $restaurant = optional(
                optional(
                    optional(
                        optional($order->items->first())->variant
                    )->product
                )->restaurant
            );

            $distance = null;
            $estimatedTime = null;

            if (
                $partner->latitude !== null &&
                $partner->longitude !== null &&
                $restaurant->latitude !== null &&
                $restaurant->longitude !== null
            ) {
                $earthRadius = 6371;

                $latDifference = deg2rad(
                    $restaurant->latitude - $partner->latitude
                );

                $lonDifference = deg2rad(
                    $restaurant->longitude - $partner->longitude
                );

                $a = sin($latDifference / 2) ** 2 +
                    cos(deg2rad($partner->latitude)) *
                    cos(deg2rad($restaurant->latitude)) *
                    sin($lonDifference / 2) ** 2;

                $distance = $earthRadius * 2 * atan2(
                    sqrt($a),
                    sqrt(1 - $a)
                );

                // Approximate estimate at 25 km/hour
                $minutes = (int) ceil(($distance / 25) * 60);

                $estimatedTime = $minutes . ' mins';
            }

            return [
                'id' => $order->id,
                'booking_number' => $order->invoice_id ?? $order->order_id,
                'price' => (float) $order->grand_total,

                'restaurant' => [
                    'name' => $restaurant->name,
                    'address' => $restaurant->address,
                    'latitude' => $restaurant->latitude,
                    'longitude' => $restaurant->longitude,
                ],

                'distance_km' => $distance !== null
                    ? round($distance, 2)
                    : null,

                'estimated_time' => $estimatedTime,

                'status' => $order->status,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'New orders fetched successfully.',
            'total_orders' => $data->count(),
            'data' => $data,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Accept Order
    |--------------------------------------------------------------------------
    */

    // public function acceptOrder(Request $request, $id)
    // {
    //     $partner = $request->user();

    //     try {

    //         $order = DB::transaction(function () use ($id, $partner) {

    //             $order = Order::where('id', $id)
    //                 ->lockForUpdate()
    //                 ->first();

    //             if (!$order) {
    //                 abort(404, 'Order not found.');
    //             }

    //             if ($order->delivery_partner_id !== null) {
    //                 abort(409, 'This order has already been assigned.');
    //             }

    //             if (in_array($order->status, ['cancelled', 'delivered'])) {
    //                 abort(409, 'This order is no longer available.');
    //             }


    //             $order->delivery_partner_id = $partner->id;
    //             $order->pickup_otp = (string) random_int(1000, 9999);
    //             $order->delivery_earning = $order->subtotal;
    //             $order->delivered_at = now();
    //             $order->status = 'delivered';
    //             $order->save();

    //             return $order;
    //         });

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Order accepted successfully.',
    //             'data' => [
    //                 'order_id' => $order->id,
    //                 'booking_number' => $order->invoice_id ?? $order->order_id,
    //                 'delivery_partner_id' => $partner->id,
    //             ],
    //         ]);

    //     } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {

    //         return response()->json([
    //             'status' => false,
    //             'message' => $e->getMessage(),
    //         ], $e->getStatusCode());

    //     } catch (\Throwable $e) {

    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Unable to accept order.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }


    public function acceptOrder(Request $request, $id)
    {
        $partner = $request->user();

        try {
            $order = DB::transaction(function () use ($id, $partner) {

                // Lock partner row to prevent simultaneous assignments
                $lockedPartner = \App\Models\DeliveryPartner::where('id', $partner->id)
                    ->lockForUpdate()
                    ->first();

                if (!$lockedPartner || $lockedPartner->status !== 'active') {
                    abort(403, 'Your delivery account is not active.');
                }

                if (
                    !$lockedPartner->is_verified ||
                    $lockedPartner->verification_status !== 'approved'
                ) {
                    abort(403, 'Your account is not approved.');
                }

                // Do not allow new orders until pending COD cash is submitted
                if ($lockedPartner->pending_hand_cash >= $lockedPartner->hand_cash_limit) {
                    abort(403, 'Please submit your pending hand cash to the restaurant.');
                }

                $order = Order::where('id', $id)
                    ->lockForUpdate()
                    ->first();

                if (!$order) {
                    abort(404, 'Order not found.');
                }

                if ($order->delivery_partner_id !== null) {
                    abort(409, 'This order has already been assigned.');
                }

                if (in_array($order->status, ['cancelled', 'delivered'])) {
                    abort(409, 'This order is no longer available.');
                }

                // Assign order only. Do not mark it delivered here.
                $order->delivery_partner_id = $lockedPartner->id;
                $order->pickup_otp = (string) random_int(1000, 9999);

                $order->save();

                return $order;
            });

            return response()->json([
                'status' => true,
                'message' => 'Order accepted successfully.',
                'data' => [
                    'order_id' => $order->id,
                    'booking_number' => $order->invoice_id ?? $order->order_id,
                    'delivery_partner_id' => $partner->id,
                    'status' => $order->status,
                ],
            ]);

        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to accept order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function orderDetails(Request $request, $id)
    {
        $partner = $request->user();

        $order = Order::with([
            'items.variant.product.restaurant',
            'user',
            'payments',
            'shipments'
        ])
            ->where('id', $id)
            ->where('delivery_partner_id', $partner->id)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Accepted order not found.'
            ], 404);
        }

        // Get restaurant from first order item
        $firstItem = $order->items->first();

        $restaurant = optional(
            optional(
                optional(
                    optional($firstItem)->variant
                )->product
            )->restaurant
        );

        // Product details
        $products = $order->items->map(function ($item) {

            $variant = $item->variant;
            $product = optional($variant)->product;

            return [
                'order_item_id' => $item->id,
                'product_id' => optional($product)->id,
                'product_name' => optional($product)->name,
                'product_image' => optional($product)->image,
                'variant_id' => optional($variant)->id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->total ?? (
                    $item->quantity * $item->price
                ),
            ];
        });

        // Customer details
        $customer = $order->user;

        return response()->json([
            'status' => true,
            'message' => 'Accepted order details fetched successfully.',

            'data' => [

                'order_details' => [
                    'order_id' => $order->id,
                    'booking_number' => $order->invoice_id ?? $order->order_id,
                    'order_status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'subtotal' => $order->subtotal,
                    'grand_total' => $order->grand_total,
                    'created_at' => $order->created_at,
                ],

                'restaurant_details' => [
                    'restaurant_id' => $restaurant->id,
                    'restaurant_name' => $restaurant->name,
                    'address' => $restaurant->address,
                    'latitude' => $restaurant->latitude,
                    'longitude' => $restaurant->longitude,
                ],

                'products' => $products,

                'customer_details' => [
                    'customer_id' => optional($customer)->id,
                    'customer_name' => optional($customer)->name,
                    'mobile' => optional($customer)->mobile,
                    'email' => optional($customer)->email,
                ],

                'customer_address' => $order->shipping_address,

                'billing_address' => $order->billing_address,

                'delivery_partner' => [
                    'id' => $partner->id,
                    'name' => $partner->name,
                    'mobile' => $partner->mobile,
                ],
            ]
        ]);
    }


    public function pickupOrder(Request $request, $id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make(
            $request->all(),
            [
                'pickup_otp' => 'required|digits:4',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $partner = $request->user();

        try {
            $order = \Illuminate\Support\Facades\DB::transaction(
                function () use ($id, $partner, $request) {

                    $order = Order::where('id', $id)
                        ->where('delivery_partner_id', $partner->id)
                        ->lockForUpdate()
                        ->first();

                    if (!$order) {
                        abort(404, 'Accepted order not found.');
                    }

                    if ($order->status === 'delivered') {
                        abort(409, 'Order is already delivered.');
                    }

                    if ($order->picked_up_at !== null) {
                        abort(409, 'Order has already been picked up.');
                    }

                    if (empty($order->pickup_otp)) {
                        abort(409, 'Pickup OTP has not been generated.');
                    }

                    $storedOtp = trim((string) $order->pickup_otp);
                    $enteredOtp = trim((string) $request->pickup_otp);

                    if ($storedOtp !== $enteredOtp) {
                        abort(422, 'Invalid pickup OTP.');
                    }

                    $order->picked_up_at = now();
                    $order->pickup_otp = null;
                    $order->status = 'picked_up';
                    $order->save();

                    return $order;
                }
            );

            return response()->json([
                'status' => true,
                'message' => 'Order picked up successfully.',
                'data' => [
                    'order_id' => $order->id,
                    'booking_number' => $order->invoice_id ?? $order->order_id,
                    'delivery_partner_id' => $partner->id,
                    'status' => $order->status,
                    'picked_up_at' => $order->picked_up_at,
                ],
            ]);

        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'Unable to pick up order.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function earnings(Request $request)
    {
        $partner = $request->user();

        $today = Carbon::today();

        // This week (Monday to Sunday)
        $thisWeekStart = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $thisWeekEnd = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        // Last week (Monday to Sunday)
        $lastWeekStart = Carbon::now()
            ->subWeek()
            ->startOfWeek(Carbon::MONDAY);

        $lastWeekEnd = Carbon::now()
            ->subWeek()
            ->endOfWeek(Carbon::SUNDAY);

        // Today's delivered orders
        $todayOrders = Order::where('delivery_partner_id', $partner->id)
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [
                $today->copy()->startOfDay(),
                $today->copy()->endOfDay()
            ])
            ->get();

        // Today's earnings
        $todayEarnings = $todayOrders->sum('delivery_earning');

        // Today's completed orders count
        $todayOrderCount = $todayOrders->count();

        // This week's earnings
        $thisWeekEarnings = Order::where('delivery_partner_id', $partner->id)
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [
                $thisWeekStart,
                $thisWeekEnd
            ])
            ->sum('delivery_earning');

        // Last week's earnings
        $lastWeekEarnings = Order::where('delivery_partner_id', $partner->id)
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [
                $lastWeekStart,
                $lastWeekEnd
            ])
            ->sum('delivery_earning');

        return response()->json([
            'status' => true,
            'message' => 'Delivery partner earnings fetched successfully.',

            'data' => [

                'today' => [
                    'date' => $today->format('Y-m-d'),
                    'orders' => $todayOrderCount,
                    'earnings' => round($todayEarnings, 2),
                ],

                'this_week' => [
                    'start_date' => $thisWeekStart->format('Y-m-d'),
                    'end_date' => $thisWeekEnd->format('Y-m-d'),
                    'earnings' => round($thisWeekEarnings, 2),
                ],

                'last_week' => [
                    'start_date' => $lastWeekStart->format('Y-m-d'),
                    'end_date' => $lastWeekEnd->format('Y-m-d'),
                    'earnings' => round($lastWeekEarnings, 2),
                ],

            ],
        ]);
    }

    public function earningHistory(Request $request)
    {
        $partner = $request->user();

        $validator = \Illuminate\Support\Facades\Validator::make(
            $request->all(),
            [
                'period' => 'required|in:today,week,month',
                'per_page' => 'nullable|integer|min:1|max:100',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $period = $request->period;
        $perPage = $request->input('per_page', 10);

        $now = Carbon::now();

        // Set date range
        if ($period === 'today') {
            $startDate = $now->copy()->startOfDay();
            $endDate = $now->copy()->endOfDay();
        } elseif ($period === 'week') {
            $startDate = $now->copy()->startOfWeek(Carbon::MONDAY);
            $endDate = $now->copy()->endOfWeek(Carbon::SUNDAY);
        } else {
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
        }

        // Delivered orders for the selected period
        $query = Order::where('delivery_partner_id', $partner->id)
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [$startDate, $endDate]);

        // Calculate total earnings and order count
        $totalEarnings = (clone $query)->sum('delivery_earning');
        $totalOrders = (clone $query)->count();

        // Individual earning history
        $history = (clone $query)
            ->select([
                'id',
                'invoice_id',
                'subtotal',
                'delivered_at',
                'status',
            ])
            ->orderBy('delivered_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Earning history fetched successfully.',

            'data' => [
                'period' => $period,

                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),

                'total_earnings' => round($totalEarnings, 2),
                'total_orders' => $totalOrders,

                'history' => $history->items(),

                'pagination' => [
                    'current_page' => $history->currentPage(),
                    'last_page' => $history->lastPage(),
                    'per_page' => $history->perPage(),
                    'total' => $history->total(),
                ],
            ],
        ]);
    }


    public function sendDeliveryOtp(Request $request, $id)
    {
        $partner = $request->user();

        try {
            $order = DB::transaction(function () use ($id, $partner) {

                $order = Order::with('user')
                    ->where('id', $id)
                    ->where('delivery_partner_id', $partner->id)
                    ->lockForUpdate()
                    ->first();

                if (!$order) {
                    abort(404, 'Order not found.');
                }

                if ($order->status !== 'picked_up') {
                    abort(409, 'You can request delivery OTP only after pickup.');
                }

                if ($order->delivered_at !== null) {
                    abort(409, 'Order is already delivered.');
                }

                if (empty(optional($order->user)->mobile)) {
                    abort(422, 'Customer mobile number not found.');
                }

                $order->delivery_otp = (string) random_int(1000, 9999);
                $order->delivery_otp_sent_at = now();
                $order->delivery_otp_expires_at = now()->addMinutes(5);
                $order->save();

                return $order;
            });

            /*
             * Send the OTP to the customer's registered mobile number
             * using your existing SMS gateway/service here.
             *
             * Customer mobile: $order->user->mobile
             * OTP: $order->delivery_otp
             */

            return response()->json([
                'status' => true,
                'message' => 'Delivery OTP generated successfully. Send it to the customer.',
                'data' => [
                    'order_id' => $order->id,
                    'booking_number' => $order->invoice_id ?? $order->order_id,
                    'customer_mobile' => $order->user->mobile,
                    'otp_expires_at' => $order->delivery_otp_expires_at,
                ],
            ]);

        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to send delivery OTP.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function verifyDeliveryOtp(Request $request, $id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make(
            $request->all(),
            [
                'delivery_otp' => 'required|digits:4',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $partner = $request->user();

        try {

            $order = DB::transaction(function () use ($id, $partner, $request) {

                $order = Order::where('id', $id)
                    ->where('delivery_partner_id', $partner->id)
                    ->lockForUpdate()
                    ->first();

                if (!$order) {
                    abort(404, 'Order not found.');
                }

                if ($order->status === 'delivered') {
                    abort(409, 'Order is already delivered.');
                }

                if ($order->status !== 'picked_up') {
                    abort(409, 'Order must be picked up before delivery.');
                }

                if (empty($order->delivery_otp)) {
                    abort(409, 'Please generate the delivery OTP first.');
                }

                if (
                    !$order->delivery_otp_expires_at ||
                    now()->greaterThan($order->delivery_otp_expires_at)
                ) {
                    abort(422, 'Delivery OTP has expired. Please request a new OTP.');
                }

                // Verify delivery OTP
                $storedOtp = trim((string) $order->delivery_otp);
                $enteredOtp = trim((string) $request->delivery_otp);

                if ($storedOtp !== $enteredOtp) {
                    abort(422, 'Invalid delivery OTP.');
                }

                // Fetch delivery partner earning from settings
                $earningSetting = Setting::where(
                    'key',
                    'delivery_partner_earning'
                )->first();

                if (!$earningSetting) {
                    abort(500, 'Delivery partner earning setting record not found.');
                }

                // Setting status: 1 = Show, 0 = Hide
                if ($earningSetting->status !== 'show') {
                    abort(500, 'Delivery partner earning setting is hidden.');
                }

                // Validate earning amount
                if (
                    $earningSetting->value === null ||
                    !is_numeric($earningSetting->value)
                ) {
                    abort(500, 'Delivery partner earning value is invalid.');
                }

                $deliveryEarning = (float) $earningSetting->value;

                // Complete delivery after successful OTP verification
                $order->status = 'delivered';
                $order->delivered_at = now();

                // Save delivery partner earning
                $order->delivery_earning = $deliveryEarning;

                // Clear delivery OTP after successful verification
                $order->delivery_otp = null;
                $order->delivery_otp_sent_at = null;
                $order->delivery_otp_expires_at = null;

                $order->save();

                return $order;
            });

            return response()->json([
                'status' => true,
                'message' => 'Order delivered successfully.',
                'data' => [
                    'order_id' => $order->id,
                    'booking_number' => $order->invoice_id ?? $order->order_id,
                    'status' => $order->status,
                    'delivery_earning' => $order->delivery_earning,
                    'delivered_at' => $order->delivered_at,
                ],
            ]);

        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'Unable to verify delivery OTP.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
