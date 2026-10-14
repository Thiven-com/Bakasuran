<?php

namespace App\Http\Resources;

use App\Models\AttributeValue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AttributeCollection extends ResourceCollection
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
                    "slug" => $data->slug,
                    "status" => $data->status,
                    "created_at" => Carbon::parse($data->created_at)->format('d-M, Y'),
                    "updated_at" => Carbon::parse($data->updated_at)->format('d-M, Y'),
                    'attribute_values' => $this->attributeValues($data->id)

                ];
            })
        ];
    }

    public function attributeValues($id) {
        $data = AttributeValue::where('attribute_id',$id)->get();
        return $data;
    }
}
