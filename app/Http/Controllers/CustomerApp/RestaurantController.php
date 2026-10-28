<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\RestaurantTable;

class RestaurantController extends Controller
{
    /**
     * Get all restaurants.
     */
    public function index(Request $request)
    {
        $restaurants = Restaurant::query()
            ->withCount('products')
            ->with([
                'products' => function ($query) {
                    $query->select([
                        'id',
                        'restaurant_id',
                        'title',
                        'slug',
                        'category_id',
                        'brand_id',
                        'image',
                        'hover_image',
                        'short_description',
                        'description',
                        'tax_id',
                        'hsn_code',
                        'status',
                        'is_feature',
                        'orders',
                        'created_at',
                        'updated_at',
                    ])->with([
                                'category:id,title',
                                'variants',
                                'variants.attributeValues',
                                'variants.attributeValues.attribute',
                            ]);
                },
            ]);

        // Optional search by restaurant name, cuisine, or city
        if ($request->filled('search')) {
            $search = trim($request->search);

            $restaurants->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('cuisine_type', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Optional city filter
        if ($request->filled('city')) {
            $restaurants->where('city', $request->city);
        }

        // Optional active restaurant filter
        if ($request->has('is_active')) {
            $restaurants->where(
                'is_active',
                $request->boolean('is_active')
            );
        }

        // Optional open restaurant filter
        if ($request->has('is_open')) {
            $restaurants->where(
                'is_open',
                $request->boolean('is_open')
            );
        }

        $perPage = max(
            1,
            min((int) $request->get('per_page', 10), 100)
        );

        $paginated = $restaurants
            ->latest('id')
            ->paginate($perPage);

        $data = $paginated->getCollection()->map(function ($restaurant) {
            return $this->formatRestaurant($restaurant);
        });

        return response()->json([
            'success' => true,
            'message' => 'Restaurants retrieved successfully.',
            'data' => $data,
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
            'links' => [
                'first' => $paginated->url(1),
                'last' => $paginated->url($paginated->lastPage()),
                'prev' => $paginated->previousPageUrl(),
                'next' => $paginated->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Get a single restaurant by ID.
     */
    public function show($id)
    {
        $restaurant = Restaurant::withCount('products')
            ->with([
                'products' => function ($query) {
                    $query->select([
                        'id',
                        'restaurant_id',
                        'title',
                        'slug',
                        'category_id',
                        'brand_id',
                        'image',
                        'hover_image',
                        'short_description',
                        'description',
                        'tax_id',
                        'hsn_code',
                        'status',
                        'is_feature',
                        'orders',
                        'created_at',
                        'updated_at',
                    ])->with([
                                'category:id,title',
                                'variants',
                                'variants.attributeValues',
                                'variants.attributeValues.attribute',
                            ]);
                },
            ])
            ->find($id);

        if (!$restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Restaurant retrieved successfully.',
            'data' => $this->formatRestaurant($restaurant),
        ]);
    }

    private function formatRestaurant($restaurant)
    {
        return [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
            'slug' => $restaurant->slug,
            'cuisine_type' => $restaurant->cuisine_type,
            'description' => $restaurant->description,

            'logo' => $restaurant->logo
                ? asset($restaurant->logo)
                : null,

            'cover_image' => $restaurant->cover_image
                ? asset($restaurant->cover_image)
                : null,

            'address' => $restaurant->address,
            'city' => $restaurant->city,
            'state' => $restaurant->state,
            'pincode' => $restaurant->pincode,
            'latitude' => $restaurant->latitude,
            'longitude' => $restaurant->longitude,

            'phone' => $restaurant->phone,
            'email' => $restaurant->email,

            'opening_time' => $restaurant->opening_time,
            'closing_time' => $restaurant->closing_time,

            'delivery_time' => $restaurant->delivery_time,
            'delivery_fee' => $restaurant->delivery_fee,
            'minimum_order' => $restaurant->minimum_order,

            'average_rating' => $restaurant->average_rating,
            'is_active' => (bool) $restaurant->is_active,
            'is_open' => (bool) $restaurant->is_open,

            'products_count' => $restaurant->products_count,
            'tables' => RestaurantTable::where('restaurant_id', $restaurant->id)
                ->get()
                ->map(function ($table) {
                    return [
                        'id' => $table->id,
                        'table_name' => $table->table_name,
                        'table_number' => $table->table_number,
                        'seating_capacity' => $table->seating_capacity,
                        'status' => $table->status,
                    ];
                })->values(),

            'products' => $restaurant->products->map(function ($product) use ($restaurant) {
                return [
                    // Restaurant details
                    'restaurant' => [
                        'id' => $restaurant->id,
                        'name' => $restaurant->name,
                        'slug' => $restaurant->slug,
                        'cuisine_type' => $restaurant->cuisine_type,
                        'description' => $restaurant->description,
                        'logo' => $restaurant->logo ? asset($restaurant->logo) : null,
                        'cover_image' => $restaurant->cover_image
                            ? asset($restaurant->cover_image)
                            : null,
                        'address' => $restaurant->address,
                        'city' => $restaurant->city,
                        'state' => $restaurant->state,
                        'pincode' => $restaurant->pincode,
                        'latitude' => $restaurant->latitude,
                        'longitude' => $restaurant->longitude,
                        'phone' => $restaurant->phone,
                        'email' => $restaurant->email,
                        'opening_time' => $restaurant->opening_time,
                        'closing_time' => $restaurant->closing_time,
                        'delivery_time' => $restaurant->delivery_time,
                        'delivery_fee' => $restaurant->delivery_fee,
                        'minimum_order' => $restaurant->minimum_order,
                        'average_rating' => $restaurant->average_rating,
                        'is_active' => (bool) $restaurant->is_active,
                        'is_open' => (bool) $restaurant->is_open,
                    ],

                    // Product details
                    'id' => $product->id,
                    'name' => $product->title,
                    'slug' => $product->slug,
                    'category_id' => $product->category_id,
                    'brand_id' => $product->brand_id,
                    'image' => $product->image ? asset($product->image) : null,
                    'hover_image' => $product->hover_image
                        ? asset($product->hover_image)
                        : null,
                    'short_description' => $product->short_description,
                    'description' => $product->description,
                    'tax_id' => $product->tax_id,
                    'hsn_code' => $product->hsn_code,
                    'status' => $product->status,
                    'is_feature' => $product->is_feature,
                    'orders' => $product->orders,
                    'created_at' => $product->created_at?->toDateTimeString(),
                    'updated_at' => $product->updated_at?->toDateTimeString(),

                    // Lowest variant price
                    'price' => $product->variants->min('price'),

                    // All variants
                    'variants' => $product->variants->map(function ($variant) {
                        return $variant->toArray();
                    })->values(),
                ];
            })->values(),

            'created_at' => $restaurant->created_at?->toDateTimeString(),
            'updated_at' => $restaurant->updated_at?->toDateTimeString(),
        ];
    }

}
