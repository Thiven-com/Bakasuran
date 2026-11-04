<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WishlistCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            $v = $item->variant;
            $p = $v?->product;

            return [
                'id' => (int) $item->id,
                'variant' => [
                    'id' => $v?->id,
                    'sku' => $v?->sku,
                    'slug' => $v?->slug,
                    'actual_price' => isset($v->actual_price) ? (float) $v->actual_price : null,
                    'price' => isset($v->price) ? (float) $v->price : null,
                    'image' => asset($v->image),
                    'discount_percent' => (
                        isset($v->actual_price, $v->price) && $v->actual_price > 0 && $v->price < $v->actual_price
                    )
                        ? round((($v->actual_price - $v->price) / $v->actual_price) * 100, 2)
                        : 0.0,
                    'stock' => $v?->stock !== null ? (int) $v->stock : null,
                    'attrs' => $v?->attributeValues?->map(fn($av) => [
                        'attribute' => $av->attribute->name ?? null,
                        'value' => $av->value ?? $av->name ?? null,
                    ])->values(),
                ],
                'product' => [
                    'id' => $item->variant?->product?->id,
                    'title' => $item->variant?->product?->title,
                    'slug' => $item->variant?->product?->slug,

                    'restaurant_id' => $item->variant?->product?->restaurant_id,
                    'restaurant_name' => $item->variant?->product?->restaurant?->name,
                ],
                'added_at' => optional($item->created_at)?->toDateTimeString(),
            ];
        })->values();
    }
}
