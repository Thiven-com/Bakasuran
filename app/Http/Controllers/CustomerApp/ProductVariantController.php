<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttributeCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\ProductVariant;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Http\Resources\VariantCollection;
use App\Http\Resources\VariantResource;
use App\Models\HomeOffer;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    public function index(Request $request)
    {
        $perPage    = 12;
        $search     = trim((string) $request->get('search', ''));
        $categoryId = $request->get('category_id');
        $minPrice   = $request->get('min_price');
        $maxPrice   = $request->get('max_price');
        $type       = $request->get('type');
        $offer_type       = $request->get('offer_type');
        $inStock    = $request->boolean('in_stock', false);
        // $sort       = $request->get('sort');
        $sort       = $request->get('sort', default: 'newest');
        $attrSlugs  = (array) $request->get('attributes', []);

        // Resolve attribute/value slugs -> IDs (cached)
        $attrMap = Cache::remember('attr_slug_to_id', 3600, function () {
            return Attribute::select('id', 'slug', 'name')->get()
                ->keyBy(fn($a) => strtolower($a->slug));
        });

        $attributeFilters = [];
        if (!empty($attrSlugs)) {
            $valMap = Cache::remember('attr_value_slug_to_id', 3600, function () {
                return AttributeValue::select('id', 'slug', 'attribute_id', 'name')->get()
                    ->groupBy('attribute_id')
                    ->map(fn($grp) => $grp->keyBy(fn($v) => strtolower($v->slug)));
            });

            foreach ($attrSlugs as $attrSlug => $valueSlugList) {
                $attrKey = strtolower($attrSlug);
                if (!isset($attrMap[$attrKey])) continue;

                $attributeId = (int)$attrMap[$attrKey]->id;
                $ids = [];
                foreach ((array) $valueSlugList as $vSlug) {
                    $vk = strtolower($vSlug);
                    if (isset($valMap[$attributeId][$vk])) {
                        $ids[] = (int)$valMap[$attributeId][$vk]->id;
                    }
                }
                if ($ids) $attributeFilters[$attributeId] = $ids;
            }
        }

        // Base query
        $query = ProductVariant::query()
            ->select('product_variants.*')
            ->join(DB::raw('(SELECT product_id, MIN(id) as min_id FROM product_variants GROUP BY product_id) first'), function ($join) {
                $join->on('product_variants.id', '=', 'first.min_id');
            })
            ->with([
                'product:id,title,slug,category_id,created_at,image,description,short_description,hsn_code',
                'product.category:id,title',
                'attributeValues:id,attribute_id,name',
                'attributeValues.attribute:id,name',
                'media:id,product_variant_id,product_id,type,url,thumbnail_url,sort_order,is_primary',
                'product.media:id,product_id,type,url,thumbnail_url,sort_order,is_primary',
            ]);

        // Apply filters
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', fn($p) => $p->where('hsn_code', "{$search}"));
            });
        }
        // if ($search) {
        //     $query->where(function ($q) use ($search) {
        //         $q->where('product_variants.sku',"{$search}")
        //             ->orWhereHas('product', fn($p) => $p->where('title', 'LIKE', "%{$search}%")
        //                 ->orWhere('description', 'LIKE', "%{$search}%")->orWhere('hsn_code', "{$search}"));
        //     });
        // }

        if (!empty($categoryId)) {
            $query->whereHas('product', fn($q) => $q->where('category_id', (int)$categoryId));
        }

        $query->when($minPrice !== null, fn($q) => $q->whereRaw('CAST(price AS DECIMAL(10,2)) >= ?', [(float)$minPrice]));
        $query->when($maxPrice !== null, fn($q) => $q->whereRaw('CAST(price AS DECIMAL(10,2)) <= ?', [(float)$maxPrice]));
        if ($inStock) $query->where('stock', '>', 0);

        foreach ($attributeFilters as $attributeId => $valueIds) {
            $query->whereHas('attributeValues', fn($q) => $q->where('attribute_values.attribute_id', $attributeId)
                ->whereIn('attribute_values.id', $valueIds));
        }

        // Type-specific
        if ($type === 'top_products') {
            $query->inRandomOrder()->take(4);
        } elseif ($type === 'product_deals') {
            $query->whereNotNull('video')->inRandomOrder()->take(4);
        } elseif ($type === 'today_deals') {
            // $query->where('today_deal', 'yes')->inRandomOrder()->take(10);
            $query->whereDate('created_at', Carbon::today());
        } else {
            $section = Section::where('section_slug', $type)->first();
            if ($section) {
                $productIds = is_array($section->product_ids) ? $section->product_ids : json_decode($section->product_ids, true);
                $query->whereIn('id', $productIds ?? []);
            }
        }

        if (!empty($offer_type)) {
            $offer = HomeOffer::where('slug', $offer_type)->first();
            $productIds = is_array($offer->product_ids)
                ? $offer->product_ids
                : json_decode($offer->product_ids, true);
            $query->whereIn('product_variants.product_id', $productIds ?? []);
        }
        $query->whereHas('product', function ($q) {
            $q->where('status', 'show');
        });

        // Sorting
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'title_asc':
                $query->join('products', 'products.id', '=', 'product_variants.product_id')
                    ->orderBy('products.title', 'asc')
                    ->select('product_variants.*');
                break;
            case 'title_desc':
                $query->join('products', 'products.id', '=', 'product_variants.product_id')
                    ->orderBy('products.title', 'desc')
                    ->select('product_variants.*');
                break;
            default:
                $query->latest('id');
        }
        // Paginate
        $variants = $query->paginate($perPage)->appends($request->query());

        return new VariantCollection($variants, [
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
    public function show($id)
    {
        $user = auth('sanctum')->user();

        $variant = ProductVariant::with([
            'product.category',
            'product.media',
            'media',
            'attributeValues.attribute',
            'product.variants.attributeValues.attribute',
        ])->findOrFail($id);

        return (new VariantResource($variant));
    }

    public function attributes(Request $request)
    {
        $attributes = Attribute::get();
        $data = new AttributeCollection($attributes);
        return response()->json([
            'success' => 1,
            'data' => $data,
            'message' => 'Data Fetched Successfully'
        ]);
    }

    public function oldIndex(Request $request)
    {
        $perPage    = 12;
        $search     = trim((string) $request->get('search', ''));
        $categoryId = $request->get('category_id');
        $minPrice   = $request->get('min_price');
        $maxPrice   = $request->get('max_price');
        $type   = $request->get('type');
        $inStock    = $request->boolean('in_stock', false);
        $sort       = $request->get('sort', default: 'newest'); // newest|price_asc|price_desc|title_asc|title_desc
        $attrSlugs  = (array) $request->get('attributes', []); // e.g. attributes[color][]=red&attributes[size][]=m

        // Resolve attribute/value slugs -> IDs (cached)
        $attrMap = Cache::remember('attr_slug_to_id', 3600, function () {
            return Attribute::select('id', 'slug', 'name')->get()
                ->keyBy(fn($a) => strtolower($a->slug));
        });

        $attributeFilters = [];
        if (!empty($attrSlugs)) {
            $valMap = Cache::remember('attr_value_slug_to_id', 3600, function () {
                return AttributeValue::select('id', 'slug', 'attribute_id', 'name')->get()
                    ->groupBy('attribute_id')
                    ->map(fn($grp) => $grp->keyBy(fn($v) => strtolower($v->slug)));
            });

            foreach ($attrSlugs as $attrSlug => $valueSlugList) {
                $attrKey = strtolower($attrSlug);
                if (!isset($attrMap[$attrKey])) continue;

                $attributeId = (int)$attrMap[$attrKey]->id;
                $ids = [];
                foreach ((array) $valueSlugList as $vSlug) {
                    $vk = strtolower($vSlug);
                    if (isset($valMap[$attributeId][$vk])) {
                        $ids[] = (int)$valMap[$attributeId][$vk]->id;
                    }
                }
                if ($ids) $attributeFilters[$attributeId] = $ids;
            }
        }

        // Base query on VARIANTS (Flipkart-style listing)
        $query = ProductVariant::query()
            ->select(['id', 'product_id', 'sku', 'slug', 'price', 'stock', 'created_at', 'image', 'actual_price', 'category_id', 'video', 'promotional_video', 'preorder', 'preorder_stock'])
            ->with([
                'product:id,title,slug,category_id,created_at,image,description,short_description',
                'product.category:id,title',
                'attributeValues:id,attribute_id,name',
                'attributeValues.attribute:id,name',
                'media:id,product_variant_id,product_id,type,url,thumbnail_url,sort_order,is_primary',
                'product.media:id,product_id,type,url,thumbnail_url,sort_order,is_primary',
            ]);

        // Search on parent product fields
        if ($search) {
            $query->where(function ($q) use ($search) {

                $q->where('product_variants.sku', 'LIKE', "%{$search}%")

                    ->orWhereHas('product', function ($p) use ($search) {
                        $p->where('products.title', 'LIKE', "%{$search}%")
                            ->orWhere('products.description', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Category filter on parent product
        if (!empty($categoryId)) {
            $query->whereHas('product', fn($q) => $q->where('category_id', (int)$categoryId));
        }

        // Price range on variant price
        // if ($minPrice !== null) $query->where('price', '>=', (float)$minPrice);
        // if ($maxPrice !== null) $query->where('price', '<=', (float)$maxPrice);

        // if ($minPrice !== null && $minPrice !== '')
        //     $query->where('price', '>=', (float)$minPrice);

        // if ($maxPrice !== null && $maxPrice !== '')
        //     $query->where('price', '<=', (float)$maxPrice);

        $query->when($minPrice !== null, function ($q) use ($minPrice) {
            $q->whereRaw('CAST(price AS DECIMAL(10,2)) >= ?', [(float)$minPrice]);
        });

        $query->when($maxPrice !== null, function ($q) use ($maxPrice) {
            $q->whereRaw('CAST(price AS DECIMAL(10,2)) <= ?', [(float)$maxPrice]);
        });
        // In-stock
        if ($inStock) $query->where('stock', '>', 0);

        // home_products


        // Attribute filters (AND across attributes, OR within attribute)
        foreach ($attributeFilters as $attributeId => $valueIds) {
            $query->whereHas('attributeValues', function ($q) use ($attributeId, $valueIds) {
                $q->where('attribute_values.attribute_id', $attributeId)
                    ->whereIn('attribute_values.id', $valueIds);
            });
        }
        if ($type !== null && $type == 'top_products') {
            $query->inRandomOrder()->take(4);
        } else if ($type !== null && $type == 'product_deals') {
            $query->whereNotNull('video')->inRandomOrder()->take(4);
        } else if ($type !== null && $type == 'today_deals') {
            $query->where('today_deal', 'yes')->inRandomOrder()->take(10);
        } else {
            $section = Section::where('section_slug', $type)->first();

            if ($section) {
                $productIds = is_array($section->product_ids)
                    ? $section->product_ids
                    : json_decode($section->product_ids, true);

                $query->whereIn('id', $productIds ?? []);
            }
        }
        // Sorting
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'title_asc':
                $query->join('products', 'products.id', '=', 'product_variants.product_id')
                    ->orderBy('products.title', 'asc')
                    ->select('product_variants.*');
                break;
            case 'title_desc':
                $query->join('products', 'products.id', '=', 'product_variants.product_id')
                    ->orderBy('products.title', 'desc')
                    ->select('product_variants.*');
                break;
            case 'newest':
            default:
                $query->latest('product_variants.id');
        }

        $variants = $query->paginate($perPage)->appends($request->query());

        return new VariantCollection($variants, [
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
}
