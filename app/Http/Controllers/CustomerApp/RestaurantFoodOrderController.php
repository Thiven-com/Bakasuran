<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RestaurantFoodOrder;
use App\Models\RestaurantFoodOrderItem;
use App\Models\RestaurantTable;
use App\Models\ProductVariant;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class RestaurantFoodOrderController extends Controller
{
    /**
     * Get restaurant food menu using table ID.
     */
    public function menu($tableId)
    {
        $table = RestaurantTable::find($tableId);

        if (!$table) {
            return response()->json([
                'status' => false,
                'message' => 'Restaurant table not found',
            ], 404);
        }

        $restaurant = Restaurant::find($table->restaurant_id);

        $products = ProductVariant::with([
            'product:id,title,slug,restaurant_id,image',
            'product.media',
            'media',
        ])
            ->whereHas('product', function ($query) use ($table) {
                $query->where('restaurant_id', $table->restaurant_id);
            })
            ->get()
            ->map(function ($variant) {

                $product = $variant->product;

                // Get variant image or media image
                $variantMedia = $variant->media->first();
                $productMedia = $product?->media->first();

                $image = $variant->image
                    ?: ($variantMedia?->thumbnail_url
                        ?: ($variantMedia?->url
                            ?: ($product?->image
                                ?: ($productMedia?->thumbnail_url
                                    ?: $productMedia?->url))));

                // Convert relative image path into URL
                if ($image && !filter_var($image, FILTER_VALIDATE_URL)) {
                    $image = asset(ltrim($image, '/'));
                }

                return [
                    'product_id' => $product?->id,
                    'product_name' => $product?->title,
                    'product_slug' => $product?->slug,

                    'product_variant_id' => $variant->id,
                    'variant_name' => $variant->name ?? null,

                    'restaurant_id' => $variant->product->restaurant_id ?? null,
                    'restaurant_name' => $variant->product->restaurant->name ?? null,

                    'image' => $image,

                    'price' => $variant->price,
                    'actual_price' => $variant->actual_price,
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Restaurant food menu fetched successfully',

            'restaurant' => [
                'restaurant_id' => $table->restaurant_id,
                'restaurant_name' => $restaurant?->name,
                'restaurant_logo' => $restaurant?->logo,
            ],

            'table' => [
                'table_id' => $table->id,
                'table_name' => $table->table_name,
                'table_number' => $table->table_number,
            ],

            'products' => $products,
        ]);
    }


    public function restaurantMenu($restaurantId)
    {
        // Find restaurant
        $restaurant = Restaurant::find($restaurantId);

        if (!$restaurant) {
            return response()->json([
                'status' => false,
                'message' => 'Restaurant not found',
            ], 404);
        }

        // Get all food products belonging to this restaurant
        $products = Product::with([
            'variants.media',
            'media',
        ])
            ->where('restaurant_id', $restaurantId)
            ->get()
            ->map(function ($product) {

                $productMedia = $product->media->first();

                $productImage = $product->image
                    ?: ($productMedia?->thumbnail_url
                        ?: $productMedia?->url);

                if ($productImage && !filter_var($productImage, FILTER_VALIDATE_URL)) {
                    $productImage = asset(ltrim($productImage, '/'));
                }

                $variants = $product->variants->map(function ($variant) use ($productImage) {

                    $variantMedia = $variant->media->first();

                    $image = $variant->image
                        ?: ($variantMedia?->thumbnail_url
                            ?: ($variantMedia?->url
                                ?: $productImage));

                    if ($image && !filter_var($image, FILTER_VALIDATE_URL)) {
                        $image = asset(ltrim($image, '/'));
                    }

                    return [
                        'product_variant_id' => $variant->id,
                        'variant_name' => $variant->name,
                        'price' => $variant->price,
                        'actual_price' => $variant->actual_price,
                        'image' => $image,
                    ];
                });

                return [
                    'product_id' => $product->id,
                    'product_name' => $product->title,
                    'product_slug' => $product->slug,
                    'restaurant_id' => $product->restaurant_id,
                    'image' => $productImage,
                    'variants' => $variants,
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Restaurant food menu fetched successfully',

            'restaurant' => [
                'restaurant_id' => $restaurant->id,
                'restaurant_name' => $restaurant->name,
            ],

            'products' => $products,
        ]);
    }

    /**
     * Place food order with multiple products.
     */

    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'restaurant_table_id' => 'required|integer|exists:restaurant_tables,id',

            'products' => 'required|array|min:1',
            'products.*.product_variant_id' => 'required|integer|distinct',
            'products.*.quantity' => 'required|integer|min:1',

            'special_instructions' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get authenticated customer using Sanctum
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated. Please login again.',
            ], 401);
        }

        $customerId = $customer->id;

        // Get selected restaurant table
        $table = RestaurantTable::find($request->restaurant_table_id);

        if (!$table) {
            return response()->json([
                'status' => false,
                'message' => 'Restaurant table not found',
            ], 404);
        }

        // Check table availability
        if ((int) $table->status !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'This restaurant table is unavailable',
            ], 422);
        }

        // Automatically get restaurant ID from selected table
        $restaurantId = $table->restaurant_id;

        try {

            return DB::transaction(function () use ($request, $customerId, $table, $restaurantId) {

                $subtotal = 0;
                $items = [];

                // Validate products and calculate subtotal
                foreach ($request->products as $product) {

                    $variant = ProductVariant::with('product')
                        ->where('id', $product['product_variant_id'])
                        ->whereHas('product', function ($query) use ($restaurantId) {
                            $query->where('restaurant_id', $restaurantId);
                        })
                        ->first();

                    if (!$variant || !$variant->product) {
                        throw ValidationException::withMessages([
                            'products' => [
                                'Selected product does not belong to this restaurant.',
                            ],
                        ]);
                    }

                    $quantity = (int) $product['quantity'];

                    $price = (float) $variant->price;

                    $total = $price * $quantity;

                    $subtotal += $total;

                    $items[] = [
                        'variant' => $variant,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $total,
                    ];
                }

                // Create restaurant food order
                $order = RestaurantFoodOrder::create([
                    'order_number' => 'RFO-' . strtoupper(Str::random(10)),

                    // Restaurant and table details
                    'restaurant_id' => $restaurantId,
                    'restaurant_table_id' => $table->id,

                    // Customer details
                    'customer_id' => $customerId,

                    // Amount details
                    'subtotal' => $subtotal,
                    'tax' => 0,
                    'grand_total' => $subtotal,

                    // Order details
                    'status' => 'pending',
                    'special_instructions' => $request->special_instructions,
                ]);

                // Save multiple food order items
                foreach ($items as $item) {

                    $variant = $item['variant'];

                    RestaurantFoodOrderItem::create([
                        'restaurant_food_order_id' => $order->id,
                        'product_variant_id' => $variant->id,
                        'product_name' => $variant->product->title,
                        'variant_name' => $variant->name ?? null,
                        'unit_price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'total_price' => $item['total'],
                    ]);
                }

                // Load order relationships
                $order->load([
                    'items',
                    'table',
                    'restaurant',
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Food order placed successfully',
                    'data' => $order,
                ], 201);

            });

        } catch (ValidationException $e) {

            return response()->json([
                'status' => false,
                'message' => 'Invalid products selected',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            \Log::error('Restaurant food order error', [
                'customer_id' => $customerId,
                'restaurant_id' => $restaurantId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to place food order. Please try again.',
            ], 500);
        }
    }


    /**
     * Customer order list.
     */
    public function index()
    {
        $orders = RestaurantFoodOrder::with([
            'items',
            'table',
            'restaurant',
        ])
            ->where('customer_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Food orders fetched successfully',
            'data' => $orders,
        ]);
    }

    /**
     * Single order details.
     */
    public function show($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $order = RestaurantFoodOrder::with([
            'items',
            'table',
            'restaurant',
        ])
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Restaurant food order not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Restaurant food order details fetched successfully',
            'data' => $order,
        ], 200);
    }

    /**
     * Update products and quantities.
     *
     * The submitted products array becomes the complete final order.
     * Omitted products are removed.
     */

    public function updateItems(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1',
            'products.*.product_variant_id' => 'required|integer|distinct',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        try {
            return DB::transaction(function () use ($request, $id, $customer) {

                $order = RestaurantFoodOrder::where('customer_id', $customer->id)
                    ->lockForUpdate()
                    ->find($id);

                if (!$order) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Food order not found for this customer',
                    ], 404);
                }

                if ($order->status !== 'pending') {
                    return response()->json([
                        'status' => false,
                        'message' => 'Only pending orders can be modified',
                    ], 422);
                }

                $subtotal = 0;
                $items = [];

                foreach ($request->products as $product) {

                    $variant = ProductVariant::with('product')
                        ->where('id', $product['product_variant_id'])
                        ->whereHas('product', function ($query) use ($order) {
                            $query->where('restaurant_id', $order->restaurant_id);
                        })
                        ->first();

                    if (!$variant || !$variant->product) {
                        throw ValidationException::withMessages([
                            'products' => [
                                'Invalid product selected for this restaurant.',
                            ],
                        ]);
                    }

                    $quantity = (int) $product['quantity'];
                    $price = (float) $variant->price;
                    $total = $price * $quantity;

                    $subtotal += $total;

                    $items[] = [
                        'variant' => $variant,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $total,
                    ];
                }

                // Remove old items
                $order->items()->delete();

                // Insert updated items
                foreach ($items as $item) {

                    $variant = $item['variant'];

                    $order->items()->create([
                        'product_variant_id' => $variant->id,
                        'product_name' => $variant->product->title,
                        'variant_name' => $variant->name ?? null,
                        'unit_price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'total_price' => $item['total'],
                    ]);
                }

                // Update order totals
                $order->update([
                    'subtotal' => $subtotal,
                    'grand_total' => $subtotal + (float) $order->tax,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Food order updated successfully',
                    'data' => $order->fresh()->load([
                        'items',
                        'table',
                        'restaurant',
                    ]),
                ]);
            });

        } catch (ValidationException $e) {

            return response()->json([
                'status' => false,
                'message' => 'Invalid product selected',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Throwable $e) {

            Log::error('Restaurant food order update failed', [
                'order_id' => $id,
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Unable to update food order',
            ], 500);
        }
    }

    /**
     * Cancel customer order.
     */

    public function cancel($id)
    {
        $customer = auth('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $order = RestaurantFoodOrder::where(
            'customer_id',
            $customer->id
        )->find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Food order not found',
            ], 404);
        }

        if (!in_array($order->status, ['pending', 'accepted'])) {
            return response()->json([
                'status' => false,
                'message' => 'This order cannot be cancelled',
            ], 422);
        }

        $order->update([
            'status' => 'cancelled',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Food order cancelled successfully',
            'data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
            ],
        ]);
    }

}
