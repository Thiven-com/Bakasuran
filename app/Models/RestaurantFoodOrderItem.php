<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantFoodOrderItem extends Model
{
    protected $fillable = [
        'restaurant_food_order_id',
        'product_variant_id',
        'product_name',
        'variant_name',
        'unit_price',
        'quantity',
        'total_price',
    ];

    public function order()
    {
        return $this->belongsTo(
            RestaurantFoodOrder::class,
            'restaurant_food_order_id'
        );
    }
}
