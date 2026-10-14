<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\FlashSaleProduct;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class VariantResource extends JsonResource
{
    public function toArray($request)
    {
        $v = $this->resource;

        $ap = $this->toFloat($v->actual_price ?? null);
        $sp = $this->toFloat($v->price ?? null);
        $thumb = $this->pickThumb($v);

        // check flash sale for current variant
        $flash = $this->getFlashSaleData($v);
        $related_products = $this->getRelatedProducts($v);

        // collect all possible values per attribute across product variants
        $switchable = [];
        if ($v->product && $v->product->variants) {
            foreach ($v->product->variants as $variant) {
                $apV = $this->toFloat($variant->actual_price ?? null);
                $spV = $this->toFloat($variant->price ?? null);
                $thumbV = $this->pickThumb($variant);
                $flashV = $this->getFlashSaleData($variant);

                foreach ($variant->attributeValues as $av) {
                    $attrId = $av->attribute?->id;
                    $attrName = $av->attribute?->name;
                    if (!$attrId || !$attrName)
                        continue;

                    if (!isset($switchable[$attrId])) {
                        $switchable[$attrId] = [
                            'id' => $attrId,
                            'name' => $attrName,
                            'values' => [],
                        ];
                    }

                    $switchable[$attrId]['values'][] = [
                        'id' => $av->id,
                        'value' => $av->value ?? $av->name,
                        'product_variant_id' => $variant->id,
                        'sku' => $variant->sku,
                        'slug' => $variant->slug,
                        'actual_price' => $apV,
                        'price' => $spV,
                        'discount_percent' => ($apV !== null && $spV !== null && $apV > 0 && $spV < $apV)
                            ? round((($apV - $spV) / $apV) * 100, 2)
                            : 0.0,
                        'stock' => (int) $variant->stock,

                        'thumbnail' => $this->normalizeUrl($thumbV),
                        'image' => $variant->image ? asset($variant->image) : null,
                        'video' => $variant->video ? asset($variant->video) : null,
                        'promotional_video' => $variant->promotional_video ? asset($variant->promotional_video) : null,
                        'media' => $variant->media->map(function ($m) {
                            return [
                                'id' => $m->id,
                                'type' => $m->type,
                                'url' => asset($m->url),
                                'thumbnail' => $m->thumbnail_url ? asset($m->thumbnail_url) : null,
                                'is_primary' => (bool) $m->is_primary,
                                'sort_order' => (int) $m->sort_order,
                            ];
                        })->values(),
                        'flash_sale' => $flashV,
                    ];
                }
            }

            // unique values by attribute-value + variant
            foreach ($switchable as $attrId => $attrData) {
                $switchable[$attrId]['values'] = collect($attrData['values'])
                    ->unique(fn($val) => $val['id'] . '-' . $val['product_variant_id'])
                    ->values()
                    ->toArray();
            }
            // foreach ($switchable as $attrId => $attrData) {
            //     $switchable[$attrId]['values'] = collect($attrData['values'])
            //         ->unique('value')
            //         ->values()
            //         ->toArray();
            // }

            $switchable = array_values($switchable);
        }
        return [
            'id' => (int) $v->id,
            'sku' => $v->sku,
            'slug' => $v->slug,
            'category_id' => $v->category_id,
            'category' => $v->category->title ?? '',
            'actual_price' => $ap,
            'price' => $sp,
            'discount_percent' => ($ap !== null && $sp !== null && $ap > 0 && $sp < $ap)
                ? round((($ap - $sp) / $ap) * 100, 2)
                : 0.0,
            'flash_sale' => $flash, // flash data for the current variant
            'related_products' => $related_products,
            'stock' => (int) $v->stock,
            'product_min_order' => $v->product_min_order,
            'product_max_order' => $v->product_max_order,
            'preorder' => $v->preorder,
            'preorder_stock' => $v->preorder_stock,
            'thumbnail' => $this->normalizeUrl($thumb),
            'image' => $v->image ? asset($v->image) : null,
            'video' => $v->video ? asset($v->video) : null,
            'promotional_video' => $v->promotional_video ? asset($v->promotional_video) : null,

            // attributes of the current variant
            'attrs' => $v->attributeValues->map(fn($val) => [
                'id' => $val->id,
                'attribute' => $val->attribute?->name,
                'attribute_id' => $val->attribute?->id,
                'value' => $val->value ?? $val->name,
            ])->values(),

            // gallery for this variant
            'media' => $v->media->map(function ($m) {
                return [
                    'id' => $m->id,
                    'type' => $m->type,
                    'url' => asset($m->url),
                    'thumbnail' => $m->thumbnail_url ? asset($m->thumbnail_url) : null,
                    'is_primary' => (bool) $m->is_primary,
                    'sort_order' => (int) $m->sort_order,
                ];
            })->values(),

            // switchable attributes now include flash sale data per variant
            'switchable_attributes' => $switchable,
            // 'variant_matrix' => $variantMatrix,
            'hsn_code' => $v->product?->hsn_code,

            'product' => [
                'id' => $v->product?->id,
                'title' => $v->product?->title,
                'slug' => $v->product?->slug,
                'description' => $v->product?->description,
                'short_description' => $v->product?->short_description,
                'hsn_code' => $v->product?->hsn_code,
            ],
            // 'variants' => $v->product->variants,
            'variants' => $v->product->variants->map(function ($variant) {

                // Variant images
                $variant->image = $variant->image ? asset($variant->image) : null;
                $variant->hover_image = $variant->hover_image ? asset($variant->hover_image) : null;

                // Variant media
                if ($variant->media) {
                    $variant->media->transform(function ($m) {
                        $m->url = $m->url ? asset($m->url) : null;
                        $m->thumbnail_url = $m->thumbnail_url ? asset($m->thumbnail_url) : null;
                        return $m;
                    });
                }

                // Product images
                if ($variant->product) {
                    $variant->product->image = $variant->product->image ? asset($variant->product->image) : null;
                    $variant->product->hover_image = $variant->product->hover_image ? asset($variant->product->hover_image) : null;

                    if ($variant->product->media) {
                        $variant->product->media->transform(function ($m) {
                            $m->url = $m->url ? asset($m->url) : null;
                            $m->thumbnail_url = $m->thumbnail_url ? asset($m->thumbnail_url) : null;
                            return $m;
                        });
                    }
                }

                return $variant;
            }),
            'created_at' => optional($v->created_at)?->toDateTimeString(),
            'ratings' =>  $this->ratings($v->product?->id),
            'is_review' => $this->reviewCheck($v->product?->id)
        ];
    }

    private function getFlashSaleData($variant): ?array
    {
        $fsp = FlashSaleProduct::where('product_variant_id', $variant->id)
            ->whereHas('flashSale', fn($q) => $q->active())
            ->with('flashSale')
            ->first();

        if (!$fsp)
            return null;

        $price = $fsp->getEffectivePrice();

        return [
            'flash_price' => $price,
            'discount_type' => $fsp->discount_type,
            'discount_value' => (float) $fsp->discount_value,
            'starts_at' => optional($fsp->flashSale->starts_at)?->toDateTimeString(),
            'ends_at' => optional($fsp->flashSale->ends_at)?->toDateTimeString(),
        ];
    }

    public function ratings($id)
    {
        $query = ProductReview::with('user')->where('product_id', $id);

        $ratings = $query->get();

        return [
            'data' => new ProductReviewCollection($ratings),
            'count' => $ratings->count(),
            'avg_rating' => round($ratings->avg('rating'), 1), // optional rounding
        ];
    }

    public function getRelatedProducts($variant)
    {
        $query = ProductVariant::query()
            // ->select(['id', 'product_id', 'sku', 'slug', 'price', 'stock', 'created_at', 'image', 'actual_price', 'category_id', 'video','preorder_stock','preorder'])
            ->select('product_variants.*')
            ->join(DB::raw('(SELECT product_id, MIN(id) as min_id FROM product_variants GROUP BY product_id) first'), function ($join) {
                $join->on('product_variants.id', '=', 'first.min_id');
            })
            ->with([
                'product:id,title,slug,category_id,created_at,image,description,short_description',
                'product.category:id,title',
                'attributeValues:id,attribute_id,name',
                'attributeValues.attribute:id,name',
                'media:id,product_variant_id,product_id,type,url,thumbnail_url,sort_order,is_primary',
                'product.media:id,product_id,type,url,thumbnail_url,sort_order,is_primary',
            ])->whereHas('product', fn($q) => $q->where('status', 'show'));
        $query = $query->where('category_id', $variant->category_id)->whereNot('id', $variant->id);
        $variants = $query->inRandomOrder()->take(8)->get();

        return new VariantCollection($variants);
    }

    private function pickThumb($v): ?string
    {
        $vm = $v->media?->sortByDesc('is_primary')->sortBy('sort_order')->first();
        $pm = $v->product?->media?->sortByDesc('is_primary')->sortBy('sort_order')->first();

        return $vm?->thumbnail_url
            ?: $vm?->url
            ?: ($pm?->thumbnail_url ?: $pm?->url ?: null);
    }

    private function normalizeUrl(?string $path): ?string
    {
        if (!$path)
            return null;
        if (preg_match('#^(https?:)?//#', $path) || str_starts_with($path, 'data:')) {
            return $path;
        }
        return asset($path);
    }

    private function toFloat($val): ?float
    {
        if ($val === null)
            return null;
        if (is_string($val)) {
            $val = trim($val);
            if ($val === '')
                return null;
            $val = preg_replace('/[^\d.\-]/', '', $val);
        }
        return is_numeric($val) ? (float) $val : null;
    }

    public function reviewCheck($id)
    {
        $user = auth('sanctum')->user();
        $query = ProductReview::where(['product_id' => $id , 'user_id' => $user->id ?? 0])->first();
        if(isset($query->id)){
            return "yes";
        }else{
            return "no";
        }
    }
}
