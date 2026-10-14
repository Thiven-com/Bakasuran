<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductsCollection extends ResourceCollection
{
    protected array $metaExtra;

    public function __construct($resource, array $metaExtra = [])
    {
        parent::__construct($resource);
        $this->metaExtra = $metaExtra;
    }

// app/Http/Resources/ProductCollection.php
public function toArray($request)
{
    return [
        'data' => $this->collection->map(function ($product) {
            $minPrice = optional($product->variants)->min('price');

            // choose thumbnail: primary media if set; else first image; else first media
            $primary = $product->media->firstWhere('is_primary', true)
                     ?? $product->media->firstWhere('type','image')
                     ?? $product->media->first();

            return [
                'id'          => $product->id,
                'name'        => $product->name,
                'slug'        => $product->slug,
                'description' => $product->description,
                'category'    => $product->category?->name,
                'price_from'  => $minPrice,
                'thumbnail'   => $primary?->thumbnail_url ?: $primary?->url,

                // full media list (client can render carousels, video players, GIFs, etc.)
                'media'       => $product->media->map(function ($m) {
                    return [
                        'id'            => $m->id,
                        'type'          => $m->type,          // image|video|gif|...
                        'mime'          => $m->mime,
                        'url'           => $m->url,
                        'thumbnail_url' => $m->thumbnail_url,
                        'is_primary'    => $m->is_primary,
                        'width'         => $m->width,
                        'height'        => $m->height,
                        'duration'      => $m->duration,
                    ];
                })->values(),

                'variants'    => $product->variants->map(function ($variant) {
                    return [
                        'id'    => $variant->id,
                        'sku'   => $variant->sku,
                        'price' => $variant->price,
                        'actual_price' => $variant->actual_price,
                        'stock' => $variant->stock,
                        'attrs' => $variant->attributeValues->map(fn($val) => [
                            'attribute' => $val->attribute->name,
                            'value'     => $val->value,
                        ])->values(),
                    ];
                })->values(),

                'created_at'  => optional($product->created_at)?->toDateTimeString(),
            ];
        })->values(),
        // 'links' => [
        //     'first' => $this->url(1),
        //     'last'  => $this->url($this->lastPage()),
        //     'prev'  => $this->previousPageUrl(),
        //     'next'  => $this->nextPageUrl(),
        // ],
        // 'meta' => array_merge([
        //     'current_page' => $this->currentPage(),
        //     'per_page'     => $this->perPage(),
        //     'total'        => $this->total(),
        //     'last_page'    => $this->lastPage(),
        // ], $this->metaExtra ?? []),
    ];
}

}
