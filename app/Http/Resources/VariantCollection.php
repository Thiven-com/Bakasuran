<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;
use App\Models\FlashSaleProduct;

class VariantCollection extends ResourceCollection
{
    protected array $metaExtra;

    public function __construct($resource, array $metaExtra = [])
    {
        parent::__construct($resource);
        $this->metaExtra = $metaExtra;
    }

    public function toArray($request)
    {
        $data = $this->collection->map(function ($v) {
            $thumb = $this->pickThumb($v);

            $ap = isset($v->actual_price) ? (float) $v->actual_price : null;
            $sp = isset($v->price) ? (float) $v->price : null;

            // 🔹 flash sale for each variant
            $flash = $this->getFlashSaleData($v);

            return [
                'id'        => $v->id,
                'sku'       => $v->sku,
                'slug'      => $v->slug,
                'category_id'      => $v->category_id,
                'category'          => $v->category?->title,
                // normal prices
                'actual_price'  => $ap,
                'price'         => $sp,
                'discount_percent' => (
                    $ap !== null && $sp !== null && $ap > 0 && $sp < $ap
                )
                    ? round((($ap - $sp) / $ap) * 100, 2)
                    : 0.0,

                // flash sale details
                'flash_sale' => $flash,

                'stock'     => $v->stock,
                'product_min_order' => $v->product_min_order,
                'product_max_order' => $v->product_max_order,
                'preorder' => $v->preorder,
                'preorder_stock' => $v->preorder_stock,
                'image'     => $v->image ? asset($v->image) : null,
                'video'     => $v->video ? asset($v->video) : null,
                'promotional_video'     => $v->promotional_video ? asset($v->promotional_video) : null,
                'hsn_code' => $v->product?->hsn_code,

                // product details
                'product'   => [
                    'id'                => $v->product?->id,
                    'title'             => $v->product?->title,
                    'slug'              => $v->product?->slug,
                    'category'          => $v->product?->category?->title,
                    'image'             => $v->product?->image ? asset($v->product->image) : null,
                    'description'       => $v->product?->description,
                    'short_description' => $v->product?->short_description,
                    'hsn_code' => $v->product?->hsn_code,
                ],

                // attributes
                'attrs' => $v->attributeValues->map(fn($val) => [
                    'id'           => $val->id,
                    'attribute'    => $val->attribute->name,
                    'attribute_id' => $val->attribute->id,
                    'value'        => $val->value ?? $val->name,
                ])->values(),

                // gallery
                'media' => $v->media->map(function ($m) {
                    return [
                        'id'        => $m->id,
                        'type'      => $m->type,
                        'url'       => asset($m->url),
                        'thumbnail' => $m->thumbnail_url ? asset($m->thumbnail_url) : null,
                        'is_primary' => (bool) $m->is_primary,
                        'sort_order' => (int) $m->sort_order,
                    ];
                })->values(),

                'thumbnail' => $thumb ? asset($thumb) : null,
                'created_at' => optional($v->created_at)?->toDateTimeString(),
            ];
        })->values();

        // 🔹 handle pagination only if resource is paginator
        if ($this->resource instanceof AbstractPaginator) {
            return [
                'data'  => $data,
                'links' => [
                    'first' => $this->url(1),
                    'last'  => $this->url($this->lastPage()),
                    'prev'  => $this->previousPageUrl(),
                    'next'  => $this->nextPageUrl(),
                ],
                'meta'  => array_merge([
                    'current_page' => $this->currentPage(),
                    'per_page'     => $this->perPage(),
                    'total'        => $this->total(),
                    'last_page'    => $this->lastPage(),
                ], $this->metaExtra),
            ];
        }

        // 🔹 fallback for simple collections
        return [
            'data' => $data,
            'meta' => $this->metaExtra,
        ];
    }

    private function getFlashSaleData($variant): ?array
    {
        $fsp = FlashSaleProduct::where('product_variant_id', $variant->id)
            ->whereHas('flashSale', fn($q) => $q->active())
            ->with('flashSale')
            ->first();

        if (!$fsp) return null;

        $price = $fsp->getEffectivePrice();

        return [
            'flash_price'   => $price,
            'flash_discount_percent' => ($variant->actual_price > 0 && $price < $variant->actual_price)
                ? round((($variant->actual_price - $price) / $variant->actual_price) * 100, 2)
                : 0.0,
            'discount_type'  => $fsp->discount_type,
            'discount_value' => (float) $fsp->discount_value,
            'starts_at'      => optional($fsp->flashSale->starts_at)?->toDateTimeString(),
            'ends_at'        => optional($fsp->flashSale->ends_at)?->toDateTimeString(),
        ];
    }

    private function pickThumb($v): ?string
    {
        $vm = $v->media->sortByDesc('is_primary')->sortBy('sort_order')->first();
        $pm = $v->product?->media
            ? $v->product->media->sortByDesc('is_primary')->sortBy('sort_order')->first()
            : null;

        return $vm?->thumbnail_url ?: $vm?->url ?: ($pm?->thumbnail_url ?: $pm?->url);
    }
}
