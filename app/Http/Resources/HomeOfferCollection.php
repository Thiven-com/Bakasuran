<?php

namespace App\Http\Resources;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HomeOfferCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $productIds = is_array($data->product_ids)
                    ? $data->product_ids
                    : json_decode($data->product_ids, true);
                $products = ProductVariant::with(['product', 'media', 'attributeValues.attribute'])
                    ->whereIn('id', $productIds ?? [])
                    ->inRandomOrder()
                    ->limit(10)
                    ->get();
                return [
                    'id' => $data->id,
                    'title' => $data->title,
                    'slug' => $data->slug,
                    'offer' => $data->offer,
                    'image' => asset($data->image),
                    'product_ids' => $productIds,
                    'products' => new VariantCollection($products),
                ];
            })
        ];
    }
}
