<?php

namespace App\Http\Resources;

use App\Models\ProductVariant;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class SectionCollection extends ResourceCollection
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
                if ($data->section_slug == 'today_deals') {
                    $products = ProductVariant::select('product_variants.*')
                        ->join(DB::raw('(SELECT product_id, MIN(id) as min_id FROM product_variants GROUP BY product_id) first'), function ($join) {
                            $join->on('product_variants.id', '=', 'first.min_id');
                        })->with(['product', 'media', 'attributeValues.attribute'])->whereDate('created_at', Carbon::today())->inRandomOrder()
                        ->whereHas('product', fn($q) => $q->where('status', 'show'))
                        ->limit(10)
                        ->get();
                } else {
                    $products = ProductVariant::with(['product', 'media', 'attributeValues.attribute'])
                        ->whereIn('id', $productIds ?? [])
                        ->whereHas('product', fn($q) => $q->where('status', 'show'))
                        ->inRandomOrder()
                        ->limit(10)
                        ->get();
                }
                return [
                    'id' => $data->id,
                    'page_name' => $data->page_name,
                    'section_name' => $data->section_name,
                    'section_slug' => $data->section_slug,
                    'title' => $data->title,
                    'short_description' => $data->short_description,
                    'image' => asset($data->image),
                    'status' => $data->status,
                    'description' => $data->description,
                    'images' => $data->images->map(function ($img) {
                        return [
                            'id'    => $img->id,
                            'image' => $img->image ? asset($img->image) : null,
                            'title' => $img->title,
                        ];
                    }),
                    'product_ids' => $productIds,
                    'products' => new VariantCollection($products),
                ];
            })
        ];
    }
}
