<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WishlistItem;
use App\Models\ProductVariant;
use App\Http\Resources\WishlistCollection;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function __construct()
    {
    }

    /** GET /api/customer/wishlist */
    public function index()
    {
        $user = auth('sanctum')->user();

        $items = WishlistItem::with([
            'variant:id,product_id,sku,slug,price,stock,image,actual_price',
            'variant.attributeValues.attribute',
            'variant.product:id,title,slug,category_id,short_description,title',
            'variant.product.category:id,title,slug',
        ])
            ->where('user_id', $user->id)
            ->latest('id')
            ->get();

        return new WishlistCollection($items);
    }

    /** POST /api/customer/wishlist  { variant_id } */
    public function store(Request $request)
    {
        $user = auth('sanctum')->user();

        // Check user authentication
        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please login first',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'variant_id' => 'required|integer|exists:product_variants,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $variant = ProductVariant::find($data['variant_id']);

        $existing = WishlistItem::where('user_id', $user->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => 1,
                'message' => 'Already in wishlist',
                'id' => $existing->id,
            ], 200);
        }

        $item = new WishlistItem();
        $item->user_id = $user->id;
        $item->product_variant_id = $variant->id;
        $item->save();

        $wishlist = WishlistItem::where('user_id', $user->id)->get();

        return response()->json([
            'success' => 1,
            'message' => 'Added to wishlist',
            'id' => $item->id,
            'data' => new WishlistCollection($wishlist),
        ], 201);
    }

    /** DELETE /api/customer/wishlist/{item} */

    public function destroy($item)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Please login first'
            ], 401);
        }

        // Find wishlist item by ID
        $wishlistItem = WishlistItem::find($item);

        if (!$wishlistItem) {
            return response()->json([
                'success' => 0,
                'message' => 'Wishlist item not found',
                'item_id' => $item
            ], 404);
        }

        // Verify ownership
        if ((int) $wishlistItem->user_id !== (int) $user->id) {
            return response()->json([
                'success' => 0,
                'message' => 'Forbidden'
            ], 403);
        }

        // Delete item
        $wishlistItem->delete();

        // Fetch remaining wishlist items
        $wishlist = WishlistItem::where('user_id', $user->id)->get();

        return response()->json([
            'success' => 1,
            'data' => new WishlistCollection($wishlist),
            'message' => 'Removed from wishlist'
        ], 200);
    }


    /** POST /api/customer/wishlist/toggle { variant_id } */
    public function toggle(Request $request)
    {
        $user = auth('sanctum')->user();

        $data = $request->validate([
            'variant_id' => 'required|integer|exists:product_variants,id',
        ]);

        $variantId = (int) $data['variant_id'];

        $item = WishlistItem::where('user_id', $user->id)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            $item->delete();
            $wishlist = WishlistItem::where('user_id', $user->id)->get();
            $data = new WishlistCollection($wishlist);
            return response()->json(['success' => 1, 'data' => $data, 'message' => 'Removed from wishlist', 'status' => 'removed']);
        }

        $new = new WishlistItem();
        $new->user_id = $user->id;
        $new->product_variant_id = $variantId;
        $new->save();

        $wishlist = WishlistItem::where('user_id', $user->id)->get();

        $data = new WishlistCollection($wishlist);

        return response()->json(['success' => 1, 'data' => $data, 'message' => 'Added to wishlist', 'status' => 'added', 'id' => $new->id], 201);
    }
}
