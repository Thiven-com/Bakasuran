<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\RestaurantReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RestaurantReviewController extends Controller
{
    // Add restaurant review
    public function store(Request $request)
    {
        $customer = Auth::guard('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not authenticated',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'order_id' => 'nullable|integer',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $existingReview = RestaurantReview::where('customer_id', $customer->id)
            ->where('restaurant_id', $request->restaurant_id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'status' => false,
                'message' => 'You have already reviewed this restaurant.',
            ], 409);
        }

        $review = RestaurantReview::create([
            'customer_id' => $customer->id,
            'restaurant_id' => $request->restaurant_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'review' => $request->review,
            'status' => 1,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Restaurant review added successfully',
            'data' => $review,
        ], 201);
    }

    // Get all reviews for a restaurant
    public function index($restaurant_id)
    {
        $reviews = RestaurantReview::with('customer:id')
            ->where('restaurant_id', $restaurant_id)
            ->where('status', 1)
            ->latest()
            ->paginate(10);

        $averageRating = RestaurantReview::where('restaurant_id', $restaurant_id)
            ->where('status', 1)
            ->avg('rating');

        return response()->json([
            'status' => true,
            'message' => 'Restaurant reviews fetched successfully',
            'average_rating' => round($averageRating ?? 0, 1),
            'total_reviews' => $reviews->total(),
            'data' => $reviews,
        ]);
    }

    // View single review
    public function show($id)
    {
        $review = RestaurantReview::with('customer:id', 'restaurant')
            ->where('status', 1)
            ->find($id);

        if (!$review) {
            return response()->json([
                'status' => false,
                'message' => 'Restaurant review not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Restaurant review details fetched successfully',
            'data' => $review,
        ]);
    }
}
