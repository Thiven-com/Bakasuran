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
use App\Models\ProductVariant;
use App\Models\AddonOption;
use Illuminate\Support\Facades\DB;
use App\Models\CartItemAddon;

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
            'mobile' => 'required|digits:10',
        ]);

        try {

            // Default OTP
            $otp = '1234';

            // Find or create customer
            $user = Customer::firstOrNew([
                'mobile' => $request->mobile
            ]);

            // Save OTP first
            $user->otp = $otp;
            $user->save();

            Log::info('Default OTP saved successfully', [
                'mobile' => $user->mobile,
                'otp' => $otp
            ]);

            // Skip WhatsApp for testing mobile number
            if ($user->mobile != '9154193014') {

                try {

                    $data = $this->sendWhatsAppMessage(
                        $user->mobile,
                        'login_verification',
                        [
                            'field_1' => $otp,
                        ]
                    );

                    $whatsappService = new WhatsAppService();

                    $result = $whatsappService->sendTemplateMessage($data);

                    Log::info('WhatsApp OTP Response', [
                        'mobile' => $user->mobile,
                        'response' => $result
                    ]);

                } catch (\Throwable $e) {

                    Log::error('WhatsApp OTP Failed', [
                        'mobile' => $user->mobile,
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);

                    // Do not stop login if WhatsApp fails
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully',
            ], 200);

        } catch (\Throwable $e) {

            Log::error('Send OTP Error', [
                'mobile' => $request->mobile,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to send OTP. Please try again.'
            ], 500);
        }
    }

    // 2. VERIFY OTP

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:4',
        ]);

        try {
            $user = Customer::where('mobile', $request->mobile)->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Customer not found',
                ], 404);
            }

            if ((string) $user->otp !== (string) $request->otp) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP',
                ], 422);
            }

            // Authenticate using the customer guard
            Auth::guard('customer')->login($user);

            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            // Clear OTP after successful verification
            $user->update([
                'otp' => null,
            ]);

            Log::info('CUSTOMER LOGIN SUCCESS', [
                'customer_id' => $user->id,
                'session_id' => $request->session()->getId(),
                'authenticated' => Auth::guard('customer')->check(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'redirect' => route('home'),
            ]);

        } catch (\Throwable $e) {

            Log::error('CUSTOMER OTP VERIFICATION ERROR', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to verify OTP. Please try again.',
            ], 500);
        }
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

    $wishlistItem = WishlistItem::firstOrCreate([
        'user_id' => $userId,
        'product_variant_id' => $request->product_variant_id,
    ]);

    return response()->json([
        'status' => true,
        'message' => $wishlistItem->wasRecentlyCreated
            ? 'Product added to wishlist.'
            : 'Product already added to wishlist.',
        'wishlist_item_id' => $wishlistItem->id
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

    $validated = $request->validate([
        'product_variant_id' => 'required|exists:product_variants,id',
        'quantity' => 'nullable|integer|min:1',
        'addon_option_ids' => 'nullable|array',
        'addon_option_ids.*' => 'required|integer|distinct|exists:addon_options,id',
    ]);

    try {
        $customerId = Auth::guard('customer')->id();
        $quantity = $validated['quantity'] ?? 1;

        $result = DB::transaction(function () use (
            $validated,
            $customerId,
            $quantity
        ) {
            $variant = ProductVariant::where(
                'id',
                $validated['product_variant_id']
            )->lockForUpdate()->firstOrFail();

            $productId = $variant->product_id;

            // Get add-on groups assigned to this product.
            $groupIds = DB::table('product_addon_groups')
                ->where('product_id', $productId)
                ->pluck('addon_group_id');

            $selectedIds = collect(
                $validated['addon_option_ids'] ?? []
            )->unique()->values();

            // Load selected options and their groups.
            $selectedAddons = AddonOption::with('group')
                ->whereIn('id', $selectedIds)
                ->whereIn('addon_group_id', $groupIds)
                ->get();

            if ($selectedAddons->count() !== $selectedIds->count()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'addon_option_ids' => [
                        'One or more selected add-ons are invalid for this product.'
                    ]
                ]);
            }

            // Check required and maximum selections for each group.
            $groups = \App\Models\AddonGroup::whereIn('id', $groupIds)
                ->where('status', 'active')
                ->get();

            foreach ($groups as $group) {
                $groupSelected = $selectedAddons->where(
                    'addon_group_id',
                    $group->id
                )->count();

                $minimum = $group->is_required
                    ? max(1, (int) $group->min_select)
                    : (int) $group->min_select;

                $maximum = $group->max_select !== null
                    ? (int) $group->max_select
                    : null;

                if (
                    strtolower($group->selection_type) === 'single'
                    && $maximum === null
                ) {
                    $maximum = 1;
                }

                if ($groupSelected < $minimum) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'addon_option_ids' => [
                            "Please select at least {$minimum} option(s) from {$group->name}."
                        ]
                    ]);
                }

                if (
                    $maximum !== null
                    && $groupSelected > $maximum
                ) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'addon_option_ids' => [
                            "You can select a maximum of {$maximum} option(s) from {$group->name}."
                        ]
                    ]);
                }
            }

            // Check add-on availability and stock.
            foreach ($selectedAddons as $addon) {
                if (!$addon->is_available) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'addon_option_ids' => [
                            $addon->name . ' is currently unavailable.'
                        ]
                    ]);
                }

                if (
                    $addon->track_stock
                    && $addon->stock < $quantity
                ) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'addon_option_ids' => [
                            'Insufficient stock for ' . $addon->name . '.'
                        ]
                    ]);
                }
            }

            // Calculate the final per-food-item price.
            $basePrice = (float) $variant->price;

            $addonTotal = $selectedAddons->sum(function ($addon) {
                return (float) $addon->price;
            });

            $finalUnitPrice = $basePrice + $addonTotal;

            // Find an existing cart item with the same variant
            // and exactly the same add-on selections.
            $cartItems = CartItem::where('user_id', $customerId)
                ->where('product_variant_id', $variant->id)
                ->where('type', 'order')
                ->with('addons')
                ->lockForUpdate()
                ->get();

            $cartItem = $cartItems->first(function ($item) use ($selectedIds) {
                $existingIds = $item->addons
                    ->pluck('addon_option_id')
                    ->filter()
                    ->map(fn($id) => (int) $id)
                    ->sort()
                    ->values();

                $requestedIds = $selectedIds
                    ->map(fn($id) => (int) $id)
                    ->sort()
                    ->values();

                return $existingIds->all() === $requestedIds->all();
            });

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->unit_price = $finalUnitPrice;
                $cartItem->save();

                $message = 'Product quantity updated in cart.';
            } else {
                $cartItem = CartItem::create([
                    'user_id' => $customerId,
                    'session_id' => null,
                    'product_variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'unit_price' => $finalUnitPrice,
                    'type' => 'order',
                ]);

                foreach ($selectedAddons as $addon) {
                    CartItemAddon::create([
                        'cart_item_id' => $cartItem->id,
                        'addon_option_id' => $addon->id,
                        'addon_name' => $addon->name,
                        'unit_price' => $addon->price,
                        'quantity' => 1,
                        'subtotal' => $addon->price,
                    ]);
                }

                $message = 'Product added to cart successfully.';
            }

            $cartCount = CartItem::where('user_id', $customerId)
             ->where('type', 'order')
                ->sum('quantity');

            return [
                'cart_item_id' => $cartItem->id,
                'cart_count' => $cartCount,
                'message' => $message,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => $result['message'],
            'cart_count' => $result['cart_count'],
            'cart_item_id' => $result['cart_item_id'],
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;
    } catch (\Throwable $e) {
        Log::error('Add to cart failed', [
            'customer_id' => Auth::guard('customer')->id(),
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'status' => false,
            'message' => 'Unable to add product to cart. Please try again.'
        ], 500);
    }
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
