<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantReview;
use Illuminate\Http\Request;

class RestaurantReviewController extends Controller
{
    // Display all restaurant reviews
    public function index(Request $request)
    {
        $query = RestaurantReview::with([
            'customer',
            'restaurant',
            'order'
        ]);

        // Filter by restaurant
        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        // Filter by review status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        $totalReviews = RestaurantReview::count();

        $activeReviews = RestaurantReview::where('status', 1)->count();

        $averageRating = RestaurantReview::where('status', 1)
            ->avg('rating');

        $restaurants = \App\Models\Restaurant::orderBy('name')->get();

        return view(
            'admin.catalog.restaurant-reviews.index',
            compact(
                'reviews',
                'totalReviews',
                'activeReviews',
                'averageRating',
                'restaurants'
            )
        );
    }

    // Activate or deactivate a review
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $review = RestaurantReview::findOrFail($id);

        $review->update([
            'status' => $request->status,
        ]);

        return back()->with(
            'success',
            'Restaurant review status updated successfully.'
        );
    }

    // Delete a review
    public function destroy($id)
    {
        $review = RestaurantReview::findOrFail($id);

        $review->delete();

        return back()->with(
            'success',
            'Restaurant review deleted successfully.'
        );
    }
}
