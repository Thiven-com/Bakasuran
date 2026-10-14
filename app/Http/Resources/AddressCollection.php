<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressCollection extends ResourceCollection
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
                    "user_id" => $data->user_id,
                    "gst" => $data->gst,
                    "mobile" => $data->mobile,
                    "alternate_mobile" => $data->alternate_mobile,
                    "email" => $data->email,
                    "landmark" => $data->landmark,
                    "address" => $data->address,
                    "address_2" => $data->address_2,
                    "city" => $data->city,
                    "city_id" => $data->city_id,
                    "pincode" => $data->pincode,
                    "state" => $data->state,
                    "state_id" => $data->state_id,
                    "default" => $data->default,
                    "created_at" => Carbon::parse($data->created_at)->format('d-M, Y'),
                    "updated_at" => Carbon::parse($data->updated_at)->format('d-M, Y'),

                ];
            })
        ];
    }
}
