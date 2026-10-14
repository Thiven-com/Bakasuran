<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\WishlistItem;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class AccountController extends Controller
{
    //
    public function login()
    {
        return view('website.login');
    }
    public function logout()
    {
        Auth::guard('customer')->logout();

        Alert::toast('Logout Successfully', 'success');

        return redirect()->route('login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required'
        ]);
        // Default OTP for all mobile numbers
        $otp = 1234;

        // $otp = rand(1000, 9999);
        if ($request->mobile == 9154193014) {
            $otp = 1234;
        }
        $check = Customer::where('mobile', $request->mobile)->first();
        if (!empty($check->id)) {
            $user = Customer::where('mobile', $request->mobile)->first();
            $user->otp = $otp;
            $user->save();
        } else {
            $user = new Customer();
            $user->mobile = $request->mobile;
            $user->otp = $otp;
            $user->save();
        }
        if ($request->mobile != 9154193014) {
            $data = $this->sendWhatsAppMessage(
                $user->mobile,
                'login_verification',
                [
                    'field_1' => $otp,
                ]
            );
            try {
                $whatsappService = new WhatsAppService();
                $result = $whatsappService->sendTemplateMessage($data);
                Log::info($result);
            } catch (\Exception $e) {
                $result = false;
                Log::info($e->getMessage());
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
        ]);
    }

    // 2. VERIFY OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'otp' => 'required'
        ]);

        $user = Customer::where('mobile', $request->mobile)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Customer not found']);
        }

        if ($user->otp !== $request->otp) {
            return response()->json(['status' => false, 'message' => 'Invalid OTP']);
        }

        // LOGIN USER
        Auth::guard('customer')->login($user);
        // clear OTP
        $user->update([
            'otp' => null,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Login successful'
        ]);
    }

    private function sendWhatsAppMessage($cust_mobile, $templateName, array $fields = [])
    {

        $data = [
            "from_phone_number_id" => "1039154362623617",
            "phone_number" => '91' . $cust_mobile,
            "template_name" => $templateName,
            "template_language" => "en_Us",
            "header_image" => "https://cdn.pixabay.com/photo/2015/01/07/15/51/woman-591576_1280.jpg",
            "header_video" => "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
            "header_document" => "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
            "header_document_name" => "",
            "header_field_1" => "{full_name}",
            "location_latitude" => "",
            "location_longitude" => "",
            "location_name" => "",
            "location_address" => "",
            "field_1" => $fields['field_1'] ?? '',
            "field_2" => $fields['field_2'] ?? '',
            "field_3" => $fields['field_3'] ?? '',
            "field_4" => $fields['field_4'] ?? '',
            "field_5" => $fields['field_5'] ?? '',
            "button_0" => $fields['field_1'],
            "button_1" => "{phone_number}",
            "copy_code" => $fields['field_1'],
        ];

        return $data;
    }



    public function addToWishlist(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Please login first.'
            ], 401);
        }

        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
        ]);

        $userId = Auth::guard('customer')->id();

        $exists = WishlistItem::where('user_id', $userId)
            ->where('product_variant_id', $request->product_variant_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => true,
                'message' => 'Product already added to wishlist.'
            ]);
        }

        $wishlistItem = new WishlistItem();
        $wishlistItem->user_id = $userId;
        $wishlistItem->product_variant_id = $request->product_variant_id;
        $wishlistItem->save();

        return response()->json([
            'status' => true,
            'message' => 'Product added to wishlist.'
        ]);
    }


    public function removeFromWishlist($id)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Please login first.'
            ], 401);
        }

        $userId = Auth::guard('customer')->id();

        $wishlistItem = WishlistItem::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$wishlistItem) {
            return response()->json([
                'status' => false,
                'message' => 'Wishlist item not found.'
            ], 404);
        }

        $wishlistItem->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product removed from wishlist.'
        ]);
    }

    public function storeAddress(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'alternate_mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'gst' => 'nullable|string|max:50',
            'pincode' => 'required|string|max:10',
            'city' => 'required|string|max:255',
            'state_id' => 'nullable|integer',
            'landmark' => 'nullable|string|max:255',
            'address' => 'required|string',
            'address_2' => 'nullable|string',
        ]);

        $validated['customer_id'] = $customer->id;

        /*
         * If customer has no address yet,
         * make this address default automatically.
         */
        $hasAddress = Address::where(
            'customer_id',
            $customer->id
        )->exists();

        $validated['is_default'] = !$hasAddress;

        Address::create($validated);

        return redirect()
            ->route('addresses')
            ->with('success', 'Address added successfully.');
    }


    public function updateAddress(Request $request, $id)
    {
        $customer = Auth::guard('customer')->user();

        $address = Address::where('id', $id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'alternate_mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'gst' => 'nullable|string|max:50',
            'pincode' => 'required|string|max:10',
            'city' => 'required|string|max:255',
            'state_id' => 'nullable|integer',
            'landmark' => 'nullable|string|max:255',
            'address' => 'required|string',
            'address_2' => 'nullable|string',
        ]);

        $address->update($validated);

        return redirect()
            ->route('addresses')
            ->with('success', 'Address updated successfully.');
    }


    public function deleteAddress($id)
    {
        $customer = Auth::guard('customer')->user();

        $address = Address::where('id', $id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        $wasDefault = $address->is_default;

        $address->delete();

        /*
         * If deleted address was default,
         * automatically make another address default.
         */
        if ($wasDefault) {

            $newDefault = Address::where(
                'customer_id',
                $customer->id
            )
                ->latest()
                ->first();

            if ($newDefault) {
                $newDefault->update([
                    'is_default' => true
                ]);
            }
        }

        return redirect()
            ->route('addresses')
            ->with('success', 'Address deleted successfully.');
    }


    public function setDefaultAddress($id)
    {
        $customer = Auth::guard('customer')->user();

        $address = Address::where('id', $id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        Address::where('customer_id', $customer->id)
            ->update([
                'is_default' => false
            ]);

        $address->update([
            'is_default' => true
        ]);

        return redirect()
            ->route('addresses')
            ->with('success', 'Default address updated successfully.');
    }


    public function addToCart(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Please login first.'
            ], 401);
        }

        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $customerId = Auth::guard('customer')->id();

        $quantity = $request->quantity ?? 1;

        $variant = \App\Models\ProductVariant::findOrFail(
            $request->product_variant_id
        );

        /*
         * Check whether this variant is already
         * available in the customer's cart.
         */
        $cartItem = \App\Models\CartItem::where('user_id', $customerId)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        if ($cartItem) {

            // If already exists, increase quantity
            $cartItem->quantity += $quantity;

            // Update latest price
            $cartItem->unit_price = $variant->price;

            $cartItem->save();

            $message = 'Product quantity updated in cart.';
        } else {

            // Add new product to cart
            $cartItem = \App\Models\CartItem::create([
                'user_id' => $customerId,
                'session_id' => null,
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $quantity,
                'unit_price' => $variant->price,
            ]);

            $message = 'Product added to cart successfully.';
        }

        /*
         * Get total cart quantity
         */
        $cartCount = \App\Models\CartItem::where('user_id', $customerId)
            ->sum('quantity');

        return response()->json([
            'status' => true,
            'message' => $message,
            'cart_count' => $cartCount,
            'cart_item_id' => $cartItem->id,
        ]);
    }

    public function removeFromCart($id)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Please login first.'
            ], 401);
        }

        $customerId = Auth::guard('customer')->id();

        $cartItem = CartItem::where('id', $id)
            ->where('user_id', $customerId)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found.'
            ], 404);
        }

        $cartItem->delete();

        $cartCount = CartItem::where('user_id', $customerId)
            ->sum('quantity');

        return response()->json([
            'status' => true,
            'message' => 'Product removed from cart.',
            'cart_count' => $cartCount
        ]);
    }


    public function updateCartQuantity(Request $request, $id)
    {
        if (!Auth::guard('customer')->check()) {
            return response()->json([
                'status' => false,
                'message' => 'Please login first.'
            ], 401);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $customerId = Auth::guard('customer')->id();

        $cartItem = CartItem::where('id', $id)
            ->where('user_id', $customerId)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'status' => false,
                'message' => 'Cart item not found.'
            ], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $itemTotal = (float) $cartItem->unit_price * (int) $cartItem->quantity;

        $cartItems = CartItem::where('user_id', $customerId)->get();

        $subtotal = $cartItems->sum(function ($item) {
            return (float) $item->unit_price * (int) $item->quantity;
        });

        /*
        |--------------------------------------------------------------------------
        | Shipping
        |--------------------------------------------------------------------------
        */

        $shipping = 0;

        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $total = $subtotal + $shipping;

        $cartCount = $cartItems->sum('quantity');

        return response()->json([
            'status' => true,
            'message' => 'Cart quantity updated successfully.',
            'quantity' => $cartItem->quantity,
            'item_total' => number_format($itemTotal, 2),
            'subtotal' => number_format($subtotal, 2),
            'shipping' => number_format($shipping, 2),
            'total' => number_format($total, 2),
            'cart_count' => $cartCount,
        ]);
    }

}
