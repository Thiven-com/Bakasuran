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


        $validator = Validator::make($request->all(), [
            'variant_id' => 'required|integer|exists:product_variants,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ]);
        }

        $data = $validator->validated();

        // ensure variant exists (and optionally in stock)
        $variant = ProductVariant::select('id')->findOrFail($data['variant_id']);

        // idempotent add (unique constraint ensures no duplicates)
        $existing = WishlistItem::where('user_id', $user->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Already in wishlist', 'id' => $existing->id]);
        }

        $item = new WishlistItem();
        $item->user_id = $user->id;
        $item->product_variant_id = $variant->id;
        $item->save();

        $wishlist = WishlistItem::where('user_id', $user->id)->get();

        $data = new WishlistCollection($wishlist);

        return response()->json(['success' => 1, 'data' => $data, 'message' => 'Added to wishlist', 'id' => $item->id], 201);
    }

    /** DELETE /api/customer/wishlist/{item} */
    public function destroy(WishlistItem $item)
    {
        $user = auth('sanctum')->user();
        if ($item->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $item->delete();

        $wishlist = WishlistItem::where('user_id', $user->id)->get();

        $data = new WishlistCollection($wishlist);

        return response()->json(['success' => 1, 'data' => $data, 'message' => 'Removed from wishlist']);
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
