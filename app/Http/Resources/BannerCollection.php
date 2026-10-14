<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BannerCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($banner) {
            return [
                'id'        => $banner->id,
                'title'     => $banner->title,
                'category_id'     => $banner->category_id,
                'offer_slug'     => $banner->offer_slug,
                'category'     => $banner->category->title ?? '',
                'image'     => asset($banner->image),
                'link'      => $banner->link_url,
                'sort_order' => $banner->sort_order,
                'starts_at' => optional($banner->starts_at)->toDateTimeString(),
                'ends_at'   => optional($banner->ends_at)->toDateTimeString(),
                'is_active' => $banner->is_active, // from accessor in Banner model
            ];
        });
    }
}
