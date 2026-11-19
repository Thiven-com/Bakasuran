<?php

namespace App\Http\Controllers\DeliveryApp;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPartner;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
        // $otp = (string) random_int(1000, 9999);
        $otp = '1234';

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
    // public function logout(Request $request)
    // {
    //     $request->user()->currentAccessToken()->delete();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Logout successful.',
    //     ]);
    // }


    public function logout(Request $request)
    {

        try {

            $user = $request->user();

            if (!$user) {

                return response()->json([

                    'status' => false,

                    'message' => 'Unauthenticated.',

                ], 401);

            }

            $token = $user->currentAccessToken();

            if (!$token) {

                return response()->json([

                    'status' => false,

                    'message' => 'No active token found.',

                ], 401);

            }

            $token->delete();

            return response()->json([

                'status' => true,

                'message' => 'Logout successful.',

            ], 200);

        } catch (\Throwable $e) {

            Log::error('Logout API Error: ' . $e->getMessage());

            return response()->json([

                'status' => false,

                'message' => 'Logout failed.',

            ], 500);

        }

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
                'latitude' => $partner->latitude,
                'longitude' => $partner->longitude,
            ],
        ]);
    }

    /**
     * Update Delivery Partner Profile
     */
    public function updateProfile(Request $request)
    {
        $partner = auth('sanctum')->user();

        if (!$partner) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',

            'mobile' => 'sometimes|string|max:20|unique:delivery_partners,mobile,' . $partner->id,

            'email' => 'sometimes|nullable|email|max:255|unique:delivery_partners,email,' . $partner->id,

            'date_of_birth' => 'sometimes|nullable|date|before:today',

            'address_line_1' => 'sometimes|nullable|string|max:255',
            'address_line_2' => 'sometimes|nullable|string|max:255',
            'landmark' => 'sometimes|nullable|string|max:255',
            'area' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:255',
            'district' => 'sometimes|nullable|string|max:255',
            'state' => 'sometimes|nullable|string|max:255',
            'country' => 'sometimes|nullable|string|max:255',
            'pincode' => 'sometimes|nullable|string|max:20',

            'profile_image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'latitude' => 'sometimes|nullable|numeric|between:-90,90',

            'longitude' => 'sometimes|nullable|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $partner->update(
            $request->only([
                'name',
                'mobile',
                'email',
                'date_of_birth',
                'address_line_1',
                'address_line_2',
                'landmark',
                'area',
                'city',
                'district',
                'state',
                'country',
                'pincode',
                'latitude',
                'longitude',
            ])
        );

        // Update profile image
        if ($request->hasFile('profile_image')) {

            // Delete old image
            if (
                $partner->profile_image &&
                Storage::disk('public')->exists($partner->profile_image)
            ) {
                Storage::disk('public')->delete($partner->profile_image);
            }

            // Upload new image
            $path = $request->file('profile_image')
                ->store('delivery-partners/profile', 'public');

            $partner->profile_image = $path;
        }

        $partner->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully.',
            'data' => [
                'id' => $partner->id,
                'partner_code' => $partner->partner_code,
                'name' => $partner->name,
                'mobile' => $partner->mobile,
                'email' => $partner->email,
                'date_of_birth' => $partner->date_of_birth,
                'profile_image' => $partner->profile_image
                    ? asset($partner->profile_image)
                    : null,
                'address' => [
                    'address_line_1' => $partner->address_line_1,
                    'address_line_2' => $partner->address_line_2,
                    'landmark' => $partner->landmark,
                    'area' => $partner->area,
                    'city' => $partner->city,
                    'district' => $partner->district,
                    'state' => $partner->state,
                    'country' => $partner->country,
                    'pincode' => $partner->pincode,
                ],
                'latitude' => $partner->latitude,
                'longitude' => $partner->longitude,
            ]
        ]);
    }

    public function updateVehicleDetails(Request $request)
    {
        $partner = $request->user();

        if (!$partner instanceof \App\Models\DeliveryPartner) {
            return response()->json([
                'status' => false,
                'message' => 'Delivery partner not authenticated.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'sometimes|required|string|max:255',
            'vehicle_number' => 'sometimes|required|string|max:255',
            'vehicle_model' => 'sometimes|nullable|string|max:255',

            'driving_license_number' => 'sometimes|nullable|string|max:255',

            'driving_license_image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'vehicle_rc_image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update vehicle details only
        $partner->vehicle_type = $request->vehicle_type ?? $partner->vehicle_type;

        $partner->vehicle_number = $request->vehicle_number ?? $partner->vehicle_number;

        if ($request->has('vehicle_model')) {
            $partner->vehicle_model = $request->vehicle_model;
        }

        if ($request->has('driving_license_number')) {
            $partner->driving_license_number = $request->driving_license_number;
        }

        // Driving License Image
        if ($request->hasFile('driving_license_image')) {

            if (
                $partner->driving_license_image &&
                \Illuminate\Support\Facades\Storage::disk('public')->exists($partner->driving_license_image)
            ) {

                \Illuminate\Support\Facades\Storage::disk('public')->delete($partner->driving_license_image);
            }

            $partner->driving_license_image = $request->file('driving_license_image')
                ->store('delivery-partners/driving-licenses', 'public');
        }

        // Vehicle RC Image
        if ($request->hasFile('vehicle_rc_image')) {

            if (
                $partner->vehicle_rc_image &&
                \Illuminate\Support\Facades\Storage::disk('public')->exists($partner->vehicle_rc_image)
            ) {

                \Illuminate\Support\Facades\Storage::disk('public')->delete($partner->vehicle_rc_image);
            }

            $partner->vehicle_rc_image = $request->file('vehicle_rc_image')
                ->store('delivery-partners/vehicle-rc', 'public');
        }

        $partner->save();

        return response()->json([
            'status' => true,
            'message' => 'Vehicle details updated successfully.',
            'data' => [
                'vehicle_type' => $partner->vehicle_type,
                'vehicle_number' => $partner->vehicle_number,
                'vehicle_model' => $partner->vehicle_model,
                'driving_license_number' => $partner->driving_license_number,

                'driving_license_image' => $partner->driving_license_image
                    ? asset($partner->driving_license_image)
                    : null,

                'vehicle_rc_image' => $partner->vehicle_rc_image
                    ? asset($partner->vehicle_rc_image)
                    : null,
            ]
        ]);
    }

    public function updateBankDetails(Request $request)
    {
        $partner = $request->user();

        if (!$partner instanceof \App\Models\DeliveryPartner) {
            return response()->json([
                'status' => false,
                'message' => 'Delivery partner not authenticated.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'account_number' => 'sometimes|required|string|max:255',
            'account_holder_name' => 'sometimes|required|string|max:255',
            'bank_name' => 'sometimes|required|string|max:255',
            'ifsc_code' => [
                'sometimes',
                'required',
                'string',
                'size:11',
                'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/i'
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $partner->fill($request->only([
            'account_number',
            'account_holder_name',
            'bank_name',
            'ifsc_code',
        ]));

        $partner->save();

        return response()->json([
            'status' => true,
            'message' => 'Bank details updated successfully.',
            'data' => [
                'account_number' => $partner->account_number,
                'account_holder_name' => $partner->account_holder_name,
                'bank_name' => $partner->bank_name,
                'ifsc_code' => $partner->ifsc_code,
            ]
        ]);
    }

    public function uploadDocuments(Request $request)
    {
        $partner = $request->user();

        if (!$partner instanceof \App\Models\DeliveryPartner) {
            return response()->json([
                'status' => false,
                'message' => 'Delivery partner not authenticated.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'aadhar_number' => [
                'sometimes',
                'required',
                'string',
                'regex:/^[0-9]{12}$/'
            ],

            'aadhar_front_image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'aadhar_back_image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'pan_number' => [
                'sometimes',
                'required',
                'string',
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/i'
            ],

            'pan_image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update Aadhaar Number
        if ($request->has('aadhar_number')) {
            $partner->aadhar_number = $request->aadhar_number;
        }

        // Update PAN Number
        if ($request->has('pan_number')) {
            $partner->pan_number = strtoupper($request->pan_number);
        }

        // Aadhaar Front Image
        if ($request->hasFile('aadhar_front_image')) {

            if (
                $partner->aadhar_front_image &&
                Storage::disk('public')->exists($partner->aadhar_front_image)
            ) {

                Storage::disk('public')->delete($partner->aadhar_front_image);
            }

            $partner->aadhar_front_image = $request->file('aadhar_front_image')
                ->store('delivery-partners/documents/aadhar', 'public');
        }

        // Aadhaar Back Image
        if ($request->hasFile('aadhar_back_image')) {

            if (
                $partner->aadhar_back_image &&
                Storage::disk('public')->exists($partner->aadhar_back_image)
            ) {

                Storage::disk('public')->delete($partner->aadhar_back_image);
            }

            $partner->aadhar_back_image = $request->file('aadhar_back_image')
                ->store('delivery-partners/documents/aadhar', 'public');
        }

        // PAN Image
        if ($request->hasFile('pan_image')) {

            if (
                $partner->pan_image &&
                Storage::disk('public')->exists($partner->pan_image)
            ) {

                Storage::disk('public')->delete($partner->pan_image);
            }

            $partner->pan_image = $request->file('pan_image')
                ->store('delivery-partners/documents/pan', 'public');
        }

        $partner->save();

        return response()->json([
            'status' => true,
            'message' => 'Documents uploaded successfully.',
            'data' => [
                'aadhar_number' => $partner->aadhar_number,
                'aadhar_front_image' => $partner->aadhar_front_image
                    ? asset($partner->aadhar_front_image)
                    : null,

                'aadhar_back_image' => $partner->aadhar_back_image
                    ? asset($partner->aadhar_back_image)
                    : null,

                'pan_number' => $partner->pan_number,

                'pan_image' => $partner->pan_image
                    ? asset($partner->pan_image)
                    : null,
            ]
        ]);
    }
}
