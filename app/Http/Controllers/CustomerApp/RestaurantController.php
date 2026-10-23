<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Get all restaurants.
     */
    public function index(Request $request)
    {
        $restaurants = Restaurant::query()
            ->withCount('products');

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

            'created_at' => $restaurant->created_at?->toDateTimeString(),
            'updated_at' => $restaurant->updated_at?->toDateTimeString(),
        ];
    }

}
