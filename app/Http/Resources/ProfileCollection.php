<?php

namespace App\Http\Resources;

use App\Models\Order;
use App\Models\WishlistItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProfileCollection extends ResourceCollection
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
                    'mobile' => $data->mobile,
                    'email' => $data->email,
                    'wallet' => $data->wallet,
                    'state_id' => $data->state_id,
                    'city_id' => $data->city_id,
                    'city' => $data->city->name ?? '',
                    'state' => $data->state->name ?? '',
                    'profile_pic' => isset($data->profile_pic) ? url($data->profile_pic) : null,
                    "created_at" => Carbon::parse($data->created_at)->format('Y-m-d'),
                    "updated_at" => Carbon::parse($data->updated_at)->format('Y-m-d'),
                    'dashboard' => $this->dashboard($data->id),
                ];
            })
        ];
    }

    public function dashboard($id) {
        $data['total_orders'] = Order::where('customer_id',$id)->where('order_type','order')->count();
        $data['preorders'] = Order::where('customer_id',$id)->where('order_type','preorder')->count();
        $data['manual_orders'] = Order::where('customer_id',$id)->where('order_type','manual_order')->count();
        $data['pending_orders'] = Order::where('customer_id',$id)->where('status','pending')->count();
        $data['wishlist'] = WishlistItem::where('user_id',$id)->count();
        return $data;
    }
}
