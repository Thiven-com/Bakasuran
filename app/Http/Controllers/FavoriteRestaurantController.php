<?php

namespace App\Http\Controllers;

use App\Models\FavoriteRestaurant;
use Illuminate\Http\Request;

class FavoriteRestaurantController extends Controller
{
    /**
     * Display the logged-in customer's favorite restaurants.
     */
    public function index()
    {
        $user = auth()->user();

        $restaurants = $user->favoriteRestaurants()
            ->latest('favorite_restaurants.created_at')
            ->paginate(12);

        return view('website.favorite-restaurants', compact('restaurants'));
    }

    /**
     * Add or remove a restaurant from favorites.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'restaurant_id' => ['required', 'integer', 'exists:restaurants,id'],
        ]);

        $userId = auth()->id();

        $restaurantId = $request->restaurant_id;

        $favorite = FavoriteRestaurant::where('user_id', $userId)
            ->where('restaurant_id', $restaurantId)
            ->first();

        if ($favorite) {

            $favorite->delete();

            return response()->json([
                'status' => true,
                'is_favorite' => false,
                'message' => 'Restaurant removed from favorites.',
            ]);
        }

        FavoriteRestaurant::create([
            'user_id' => $userId,
            'restaurant_id' => $restaurantId,
        ]);

        return response()->json([
            'status' => true,
            'is_favorite' => true,
            'message' => 'Restaurant added to favorites.',
        ]);
    }

    /**
     * Remove a restaurant from favorites.
     */
    public function destroy($id)
    {
        FavoriteRestaurant::where('user_id', auth()->id())
            ->where('restaurant_id', $id)
            ->delete();

        return redirect()
            ->back()
            ->with('success', 'Restaurant removed from favorites.');
    }

}
