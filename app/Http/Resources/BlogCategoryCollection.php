<?php

namespace App\Http\Resources;

use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogCategoryCollection extends ResourceCollection
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
                    "name" => $data->name,
                    "description" => $data->description,
                    "image" => isset($data->image) ? asset($data->image) : null,
                    "created_at" => Carbon::parse($data->created_at)->format('d-M, Y'),
                    "updated_at" => Carbon::parse($data->updated_at)->format('d-M, Y'),
                    'count' => $this->blogsCount($data->id)

                ];
            })
        ];
    }

    public function blogsCount($id) {
        $count = Blog::where('category_id',$id)->count();
        return $count;
    }
}
