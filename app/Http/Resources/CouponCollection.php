<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CouponCollection extends ResourceCollection
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
                    'name' => $data->name,
                    'code' => $data->code,
                    'type' => $data->type,
                    'discount' => $data->discount,
                    'minimum_purchase' => $data->minimum_purchase,
                    'status' => $data->status,
                    // 'limit' => $data->limit,
                    'expiry_date' => $data->expiry_date,
                    'description' => $data->description,
                    'created_at' => $data->created_at,
                ];
            })
        ];
    }
}
