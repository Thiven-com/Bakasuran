<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
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
                return [
                    'id' => $data->id,
                    'title' => $data->title,
                    'slug' => $data->slug,
                    'parent_id' => $data->parent_id,
                    'icon' => $data->icon,
                    'image' => asset($data->image),
                    'status' => $data->status,
                    'description' => $data->description,
                ];
            })
        ];
    }
}
