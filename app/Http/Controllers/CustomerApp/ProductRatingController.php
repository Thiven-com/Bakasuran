<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\ProductRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductRatingController extends Controller
{
    // Add product rating
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
            'product_id' => 'required|exists:products,id',
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

        $product = \App\Models\Product::find($request->product_id);

        if (!$product || !$product->restaurant_id) {
            return response()->json([
                'status' => false,
                'message' => 'Restaurant not found for this product',
            ], 404);
        }

        $existingRating = ProductRating::where('customer_id', $customer->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingRating) {
            return response()->json([
                'status' => false,
                'message' => 'You have already rated this product.',
            ], 409);
        }

        $rating = ProductRating::create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'restaurant_id' => $product->restaurant_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'review' => $request->review,
            'status' => 1,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product rating added successfully',
            'data' => $rating,
        ], 201);
    }

    // Get all ratings of a product
    public function index($product_id)
    {
        $ratings = ProductRating::with('customer')
            ->where('product_id', $product_id)
            ->where('status', 1)
            ->latest()
            ->paginate(10);

        $averageRating = ProductRating::where('product_id', $product_id)
            ->where('status', 1)
            ->avg('rating');

        $totalRatings = ProductRating::where('product_id', $product_id)
            ->where('status', 1)
            ->count();

        return response()->json([
            'status' => true,
            'message' => 'Product ratings fetched successfully',
            'average_rating' => round($averageRating ?? 0, 1),
            'total_ratings' => $totalRatings,
            'data' => $ratings,
        ]);
    }

    // View single rating
    public function show($id)
    {
        $ratings = ProductRating::with('customer', 'product')
            ->where('product_id', $id)
            ->where('status', 1)
            ->get();

        if ($ratings->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Product ratings not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product ratings fetched successfully',
            'average_rating' => round($ratings->avg('rating'), 1),
            'total_ratings' => $ratings->count(),
            'data' => $ratings,
        ]);
    }

    public function getRatingsByProduct($productId)
    {
        $ratings = ProductRating::with('customer', 'product')
            ->where('product_id', $productId)
            ->where('status', 1)
            ->get();

        if ($ratings->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Product ratings not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product ratings fetched successfully',
            'product_id' => (int) $productId,
            'average_rating' => round($ratings->avg('rating'), 1),
            'total_ratings' => $ratings->count(),
            'data' => $ratings,
        ]);
    }

    public function getAllRatings()
    {
        $ratings = ProductRating::where('status', 1)
            ->select('product_id', 'rating')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $ratings,
        ]);
    }

    // Update own rating
    public function update(Request $request, $id)
    {
        $customer = Auth::guard('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not authenticated',
            ], 401);
        }

        $rating = ProductRating::where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$rating) {
            return response()->json([
                'status' => false,
                'message' => 'Product rating not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $rating->update($request->only([
            'rating',
            'review',
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Product rating updated successfully',
            'data' => $rating,
        ]);
    }

    // Delete own rating
    public function destroy($id)
    {
        $customer = Auth::guard('sanctum')->user();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not authenticated',
            ], 401);
        }

        $rating = ProductRating::where('id', $id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$rating) {
            return response()->json([
                'status' => false,
                'message' => 'Product rating not found',
            ], 404);
        }

        $rating->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product rating deleted successfully',
        ]);
    }
}
