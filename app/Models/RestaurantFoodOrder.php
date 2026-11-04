<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantFoodOrder extends Model
{
    protected $fillable = [
        'order_number',
        'restaurant_id',
        'restaurant_table_id',
        'customer_id',
        'subtotal',
        'tax',
        'grand_total',
        'status',
        'special_instructions',
    ];

    public function items()
    {
        return $this->hasMany(
            RestaurantFoodOrderItem::class,
            'restaurant_food_order_id'
        );
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function table()
    {
        return $this->belongsTo(
            RestaurantTable::class,
            'restaurant_table_id'
        );
    }
}
