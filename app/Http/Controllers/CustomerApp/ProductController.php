<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductReviewCollection;
use Illuminate\Support\Facades\Cache;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage    = (int) $request->get('per_page', 12);
        $search     = trim((string) $request->get('search', ''));
        $categoryId = $request->get('category_id');
        $minPrice   = $request->get('min_price');
        $maxPrice   = $request->get('max_price');
        $inStock    = filter_var($request->get('in_stock'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $sortInput  = $request->get('sort', 'newest'); // newest|price_asc|price_desc|title_asc|title_desc|name_asc|name_desc
        $attrSlugs  = (array) $request->get('attributes', []); // e.g. attributes[color][]=red

        // Map legacy 'name_*' to 'title_*' if client still sends old keys
        $sort = match ($sortInput) {
            'name_asc'  => 'title_asc',
            'name_desc' => 'title_desc',
            default     => $sortInput,
        };

        // 1) Resolve attribute/value slugs -> IDs (cached)
        $attrMap = Cache::remember('attr_slug_to_id', 3600, function () {
            return Attribute::select('id', 'slug', 'name')->get()
                ->keyBy(fn($a) => strtolower($a->slug));
        });

        $attributeFilters = [];
        if (!empty($attrSlugs)) {
            $valMap = Cache::remember('attr_value_slug_to_id', 3600, function () {
                return AttributeValue::select('id', 'slug', 'attribute_id', 'value')->get()
                    ->groupBy('attribute_id')
                    ->map(fn($group) => $group->keyBy(fn($v) => strtolower($v->slug)));
            });

            foreach ($attrSlugs as $attrSlug => $valueSlugList) {
                $attrSlugKey = strtolower($attrSlug);
                if (!isset($attrMap[$attrSlugKey])) continue;

                $attributeId = (int) $attrMap[$attrSlugKey]->id;
                $valueIds = [];
                foreach ((array) $valueSlugList as $vSlug) {
                    $vSlugKey = strtolower($vSlug);
                    if (isset($valMap[$attributeId][$vSlugKey])) {
                        $valueIds[] = (int) $valMap[$attributeId][$vSlugKey]->id;
                    }
                }
                if ($valueIds) {
                    $attributeFilters[$attributeId] = $valueIds;
                }
            }
        }

        // 2) Base query (use 'title' consistently; load media instead of images)
        $query = Product::query()
            ->select(['id', 'title', 'slug', 'description', 'category_id', 'created_at', 'image'])
            ->with([
                'category:id,title',
                // Load a small media set for list view; includes images/videos/gifs
                'media' => fn($q) => $q->select('id', 'product_id', 'type', 'url', 'thumbnail_url', 'sort_order', 'is_primary')
                    ->orderByDesc('is_primary')
                    ->orderBy('sort_order')
                    ->limit(5),
                // Variants minimal fields
                'variants:id,product_id,sku,sale_price,stock',
                'variants.attributeValues:id,attribute_id,name',
                'variants.attributeValues.attribute:id,name',
            ]);

        // 3) Search (use 'title' + 'description')
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 4) Category filter
        if (!empty($categoryId)) {
            $query->where('category_id', (int) $categoryId);
        }

        // 5) Price range via variants
        if ($minPrice !== null || $maxPrice !== null) {
            $query->whereHas('variants', function ($q) use ($minPrice, $maxPrice) {
                if ($minPrice !== null) $q->where('price', '>=', (float) $minPrice);
                if ($maxPrice !== null) $q->where('price', '<=', (float) $maxPrice);
            });
        }

        // 6) In-stock products (any variant with stock > 0)
        if ($inStock === true) {
            $query->whereHas('variants', fn($q) => $q->where('stock', '>', 0));
        }

        // 7) Attribute filters (AND across attributes, OR within same attribute)
        foreach ($attributeFilters as $attributeId => $valueIds) {
            $query->whereHas('variants.attributeValues', function ($q) use ($attributeId, $valueIds) {
                $q->where('attribute_values.attribute_id', $attributeId)
                    ->whereIn('attribute_values.id', $valueIds);
            });
        }

        // 8) Sorting
        switch ($sort) {
            case 'price_asc':
                $query->withMin('variants', 'price')->orderBy('variants_min_price', 'asc');
                break;
            case 'price_desc':
                $query->withMin('variants', 'price')->orderBy('variants_min_price', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'newest':
            default:
                $query->latest('id');
        }

        // 9) Pagination
        $paginated = $query->paginate($perPage)->appends($request->query());

        // 10) Return collection
        return new ProductCollection($paginated, [
            'applied_filters' => [
                'search'      => $search ?: null,
                'category_id' => $categoryId ? (int)$categoryId : null,
                'min_price'   => $minPrice !== null ? (float)$minPrice : null,
                'max_price'   => $maxPrice !== null ? (float)$maxPrice : null,
                'in_stock'    => $inStock,
                'attributes'  => $attrSlugs,
                'sort'        => $sort,
                'per_page'    => $perPage,
            ],
        ]);
    }



    public function productRating(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!isset($user->id)) {
            return response()->json([
                'success' => 9,
                'message' => 'Please Login'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'rating' => 'required|numeric|min:1|max:5',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }
        $product = Product::where('id', $request->product_id)->first();
        if (!isset($product->id)) {
            return response()->json([
                'success' => 0,
                'message' => 'Product Details Not Found'
            ]);
        }
        $ratingCheck = ProductReview::where(['product_id' => $product->id, 'user_id' => $user->id])->first();
        if (isset($ratingCheck->id)) {
            return response()->json([
                'success' => 0,
                'message' => 'You have already submitted a rating for this product.'
            ]);
        }
        $review = new ProductReview();
        $review->user_id = $user->id;
        $review->product_id = $product->id;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();
        return response()->json([
            'success' => 1,
            'message' => 'Your review has been submitted successfully.'
        ]);
    }
    public function ratings(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!isset($user->id)) {
            return response()->json([
                'success' => 9,
                'message' => 'Please Login'
            ]);
        }

        $ratings = ProductReview::where(['user_id' => $user->id])->get();
        $data = new ProductReviewCollection($ratings);
        return response()->json([
            'success' => 1,
            'data' => $data,
            'message' => 'Data Fetched Succesdfully.'
        ]);
    }
}
