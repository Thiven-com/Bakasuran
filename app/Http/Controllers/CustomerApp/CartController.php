<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\FlashSaleProduct;
use App\Http\Resources\CartCollection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');
    }

    /** List current user's cart */
    public function index()
    {
        $user = auth('sanctum')->user();

        $items = CartItem::with([
            'variant.product:id,title,slug',
            'variant.attributeValues.attribute'
        ])
            ->where('user_id', $user->id)
            ->get();

        return new CartCollection($items);
    }

    public function add(Request $request)
    {
        $user = auth('sanctum')->user();

        $validator = Validator::make($request->all(), [
            'variant_id' => 'required|integer|exists:product_variants,id',
            'quantity' => 'sometimes|integer|min:1|max:999',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }

        $data = $validator->validated();
        $qty = $data['quantity'] ?? 1;

        $variant = ProductVariant::select(
            'id',
            'price',
            'stock',
            'actual_price',
            'product_min_order',
            'product_max_order',
            'preorder',
            'preorder_stock'
        )->findOrFail($data['variant_id']);

        // ✅ Stock logic
        $isPreorder = $variant->preorder ?? false;

        if ($variant->stock < 1) {
            if ($isPreorder) {
                if ($variant->preorder_stock < 1) {
                    return response()->json([
                        'success' => 0,
                        'message' => 'Preorder stock not available'
                    ]);
                }
                $availableStock = $variant->preorder_stock;
            } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'Out of stock'
                ]);
            }
        } else {
            $availableStock = $variant->stock;
        }

        // ✅ Decide type
        $type = ($variant->stock < 1 && $isPreorder) ? 'preorder' : 'order';

        // ✅ Min / Max
        $minQty = $variant->product_min_order ?? 1;
        $maxQty = $variant->product_max_order ?? 999;

        $qty = max($qty, $minQty);
        $qty = min($qty, $maxQty);
        $qty = min($qty, $availableStock);

        $price = $this->getEffectivePrice($variant);

        $item = CartItem::where('user_id', $user->id)
            ->where('product_variant_id', $variant->id)
            ->first();

        if ($item) {
            $newQty = $qty;

            $newQty = max($newQty, $minQty);
            $newQty = min($newQty, $maxQty);
            $newQty = min($newQty, $availableStock);

            $item->update([
                'quantity' => $newQty,
                'unit_price' => $price,
                'type' => $type,
            ]);
        } else {
            $item = new CartItem();
            $item->user_id = $user->id;
            $item->product_variant_id = $variant->id;
            $item->quantity = $qty;
            $item->unit_price = $price;
            $item->type = $type;
            $item->save();
        }

        $items = CartItem::with([
            'variant.product:id,title,slug',
            'variant.attributeValues.attribute'
        ])
            ->where('user_id', $user->id)
            ->get();

        $data = new CartCollection($items);

        return response()->json([
            'success' => 1,
            'data' => $data,
            'message' => 'Added to cart',
            'id' => $item->id
        ]);
    }
    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => 0,
                'message' => 'Unauthenticated',
            ], 401);
        }

        if ($cartItem->user_id !== $user->id) {
            return response()->json([
                'success' => 0,
                'message' => 'Forbidden',
            ], 403);
        }

        $variant = ProductVariant::select(
            'id',
            'price',
            'stock',
            'actual_price',
            'product_min_order',
            'product_max_order',
            'preorder',
            'preorder_stock'
        )->findOrFail($cartItem->product_variant_id);

        $minQty = $variant->product_min_order ?? 1;
        $maxQty = $variant->product_max_order ?? 10;

        $validator = Validator::make($request->all(), [
            'quantity' => [
                'required',
                'integer',
                "min:$minQty",
                "max:$maxQty",
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $qty = $validator->validated()['quantity'];

        // Determine stock source
        $isPreorder = (bool) $variant->preorder;

        if ($variant->stock < 1) {
            if ($isPreorder) {
                if ($variant->preorder_stock < 1) {
                    return response()->json([
                        'success' => 0,
                        'message' => 'Preorder stock not available',
                    ], 422);
                }

                $availableStock = $variant->preorder_stock;
                $type = 'preorder';
            } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'Out of stock',
                ], 422);
            }
        } else {
            $availableStock = $variant->stock;
            $type = 'order';
        }

        // Don't silently change an invalid requested quantity
        if ($qty > $availableStock) {
            return response()->json([
                'success' => 0,
                'message' => "Only {$availableStock} items available.",
                'available_stock' => $availableStock,
            ], 422);
        }

        $price = $this->getEffectivePrice($variant);

        $cartItem->quantity = $qty;
        $cartItem->unit_price = $price;
        $cartItem->type = $type;
        $cartItem->save();

        return response()->json([
            'success' => 1,
            'message' => 'Quantity updated',
            'quantity' => $qty,
        ]);
    }
    public function remove($id)
    {
        $user = auth('sanctum')->user();

        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json([
                'success' => 0,
                'message' => 'Cart item not found'
            ], 404);
        }

        if ($cartItem->user_id !== $user->id) {
            return response()->json([
                'success' => 0,
                'message' => 'Forbidden'
            ], 403);
        }

        $cartItem->delete();

        return response()->json([
            'success' => 1,
            'message' => 'Removed from cart'
        ]);
    }

    /** Clear the entire cart for current user */
    public function clear()
    {
        $user = auth('sanctum')->user();

        CartItem::where('user_id', $user->id)->delete();
        return response()->json(['success' => 1, 'message' => 'Cart cleared']);
    }

    /** 🔹 Helper: Get effective price (flash sale or normal) */
    private function getEffectivePrice(ProductVariant $variant)
    {
        $fsp = FlashSaleProduct::where('product_variant_id', $variant->id)
            ->whereHas('flashSale', function ($q) {
                $q->where('status', 1)
                    ->where('starts_at', '<=', Carbon::now())
                    ->where('ends_at', '>=', Carbon::now());
            })
            ->first();

        if ($fsp) {
            if ($fsp->discount_type === 'fixed') {
                return max(0, $variant->price - $fsp->discount_value);
            } elseif ($fsp->discount_type === 'percent') {
                return max(0, $variant->price * (1 - $fsp->discount_value / 100));
            }
        }

        return $variant->price; // fallback normal price
    }
}
