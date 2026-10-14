<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogCollection extends ResourceCollection
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
                    "id" => $data->id,
                    "category_id" => $data->category_id,
                    "category" => $data->category->name ?? '',
                    "title" => $data->title,
                    "slug" => $data->slug,
                    "short_description" => $data->short_description,
                    "description" => $data->description,
                    "tags" => $data->tags,
                    "facebook" => $data->facebook,
                    "instagram" => $data->instagram,
                    "youtube" => $data->youtube,
                    "image" => isset($data->image) ? asset($data->image) : null,
                    "banner" => isset($data->banner) ? asset($data->banner) : null,
                    "created_at" => Carbon::parse($data->created_at)->format('d-M, Y'),
                    "updated_at" => Carbon::parse($data->updated_at)->format('d-M, Y'),
                ];
            })
        ];
    }
}
