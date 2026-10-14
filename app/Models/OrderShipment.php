<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShipment extends Model
{
    protected $casts = [
        'shipping_address' => 'array'
    ];
public function items()
{
    // order_items.order_id == order_shipments.order_id
    return $this->hasMany(\App\Models\OrderItem::class, 'order_id', 'order_id');
}
}
