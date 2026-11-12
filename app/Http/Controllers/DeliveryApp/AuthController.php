<?php

namespace App\Http\Controllers\DeliveryApp;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPartner;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * First-time registration OR existing partner login
     */
    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string|max:20',
        ]);

        // Check whether mobile number already exists
        $partner = DeliveryPartner::where('mobile', $request->mobile)
            ->first();

        // First-time registration
        if (!$partner) {

            $partner = DeliveryPartner::create([
                'partner_code' => 'DP' . strtoupper(Str::random(8)),
                'name' => 'Delivery Partner',
                'mobile' => $request->mobile,
                'status' => 'active',
                'is_verified' => false,
                'verification_status' => 'pending',
            ]);

            $accountType = 'registration';

        } else {

            // Existing partner login
            if ($partner->status !== 'active') {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is inactive or blocked.',
                ], 403);
            }

            $accountType = 'login';
        }

        // Generate 4-digit OTP
        $otp = (string) random_int(1000, 9999);

        // Save OTP in database
        $partner->update([
            'otp' => $otp,
        ]);

        // Integrate SMS gateway here to send OTP
        // Example: send $otp to $partner->mobile

        return response()->json([
            'status' => true,
            'message' => $accountType === 'registration'
                ? 'Registration OTP generated successfully.'
                : 'Login OTP generated successfully.',
            'account_type' => $accountType,
            'mobile' => $partner->mobile,
        ]);
    }

    /**
     * Verify OTP and complete registration/login
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string|max:20',
            'otp' => 'required|digits:4',
        ]);

        $partner = DeliveryPartner::where('mobile', $request->mobile)
            ->first();

        if (!$partner) {
            return response()->json([
                'status' => false,
                'message' => 'Delivery partner not found.',
            ], 404);
        }

        if ($partner->status !== 'active') {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive or blocked.',
            ], 403);
        }

        if (!$partner->otp || $partner->otp !== $request->otp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP.',
            ], 422);
        }

        // Complete mobile verification
        $partner->update([
            'otp' => null,
            'is_verified' => true,
            'last_login_at' => now(),
        ]);

        // Generate Sanctum token
        $token = $partner->createToken('delivery-partner-app')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'data' => [
                'delivery_partner' => $partner->fresh(),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout successful.',
        ]);
    }

    public function home(Request $request)
    {
        $partner = $request->user();

        // Total orders assigned to this delivery partner
        $totalOrders = Order::where('delivery_partner_id', $partner->id)
            ->count();

        // Today's earnings
        $todayEarnings = Order::where('delivery_partner_id', $partner->id)
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'delivered')
            ->sum('grand_total');

        return response()->json([
            'status' => true,
            'message' => 'Delivery partner home data fetched successfully.',
            'data' => [
                'delivery_partner' => [
                    'id' => $partner->id,
                    'partner_code' => $partner->partner_code,
                    'name' => $partner->name,
                    'mobile' => $partner->mobile,
                    'profile_image' => $partner->profile_image,
                    'is_verified' => $partner->is_verified,
                    'is_available' => $partner->is_available,
                    'status' => $partner->status,
                ],
                'order_statistics' => [
                    'total_orders' => $totalOrders,
                    'today_earnings' => number_format($todayEarnings, 2, '.', ''),
                ],

            ],
        ]);
    }

    public function profile(Request $request)
    {
        $partner = $request->user();

        return response()->json([
            'status' => true,
            'message' => 'Profile fetched successfully.',
            'data' => [
                'id' => $partner->id,
                'name' => $partner->name,
                'image' => $partner->profile_image
                    ? asset($partner->profile_image)
                    : null,
            ],
        ]);
    }
}
