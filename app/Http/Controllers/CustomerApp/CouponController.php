<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponCollection;
use App\Models\Coupon;
use App\Models\CouponUsageHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    //
    public function coupons(Request $request)
    {
        $coupons = Coupon::query();
        $coupons = $coupons->orWhereDate('expiry_date', '>=', Carbon::today())->latest()->paginate(20);
        // $coupons = $coupons->orWhere('expiry_date', '>=', Carbon::today())->latest()->paginate(20);
        $data = new CouponCollection($coupons);
        if (count($data) > 0) {
            return response()->json([
                'success' => 1,
                'data' => $data,
                'message' => "Coupons Fetched Successfully."
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'No Coupons Found'
            ]);
        }
    }
    public function applyCoupon(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!isset($user->id)) {
            return response()->json([
                'success' => 0,
                'message' => 'Please Login'
            ]);
        }
        $type = $request->order_type ?? 'order';
        $cart = \App\Models\CartItem::with(['variant.product'])
            ->where('user_id', $user->id)->where('type', $type)
            ->get();

        if ($cart->isEmpty()) {
            return response()->json(['success' => 0, 'message' => 'Cart is empty']);
        }
        $subtotal = 0.00;

        foreach ($cart as $line) {
            $variant = \App\Models\ProductVariant::with('product')
                ->select('id', 'price', 'stock', 'product_id', 'sku')
                ->findOrFail($line->product_variant_id);

            $unitPrice = (float)$variant->price;
            $lineTotal = $unitPrice * (int)$line->quantity;
            $subtotal += $lineTotal;
        }
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $coupon = Coupon::where('code', $request->code)
            ->where('status', 'active')
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid or Inactive coupon code'
            ]);
        }

        // Check expiry
        if ($coupon->expiry_date && Carbon::parse($coupon->expiry_date)->endOfDay()->isPast()) {
            return response()->json([
                'success' => 0,
                'message' => 'Coupon has expired'
            ]);
        }
        $check = CouponUsageHistory::where(['user_id' => $user->id, 'coupon_id' => $coupon->id])->first();
        if (isset($check->id)) {
            return response()->json([
                'success' => 0,
                'message' => 'Coupon Already Used'
            ]);
        }
        // Minimum purchase check
        if ($coupon->minimum_purchase && $subtotal < $coupon->minimum_purchase) {
            return response()->json([
                'success' => 0,
                'message' => 'Minimum purchase amount is ₹' . $coupon->minimum_purchase
            ]);
        }

        // Usage limit check
        if ($coupon->limit !== null && $coupon->limit <= 0) {
            return response()->json([
                'success' => 0,
                'message' => 'Coupon usage limit exceeded'
            ]);
        }

        // Calculate discount
        if ($coupon->type === 'fixed') {
            $discount = min($coupon->discount, $subtotal);
        } else {
            $discount = ($subtotal * $coupon->discount) / 100;
        }

        $finalAmount = max($subtotal - $discount, 0);

        return response()->json([
            'success' => 1,
            'message' => 'Coupon applied successfully',
            'data' => [
                'coupon_code' => $coupon->code,
                'discount_type' => $coupon->type,
                'discount' => round($discount),
                'original_amount' => $subtotal,
                'payable_amount' => round($finalAmount, 2),
            ]
        ]);
    }
}
