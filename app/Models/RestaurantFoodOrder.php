<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RestaurantTableBooking;

class RestaurantFoodOrder extends Model
{
    protected $fillable = [
        'order_number',
        'restaurant_id',
        'restaurant_table_id',
        'restaurant_table_booking_id',
        'customer_id',
        'subtotal',
        'tax',
        'grand_total',
        'status',
        // Bill and Payment
        'bill_generated_at',
        'payment_method',
        'payment_status',
        'payment_transaction_id',
        'payment_gateway_order_id',
        'paid_at',
        'special_instructions',
    ];

    protected $casts = [
        'bill_generated_at' => 'datetime',
        'paid_at' => 'datetime',
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

    public function booking()
    {
        return $this->belongsTo(
            RestaurantTableBooking::class,
            'restaurant_table_booking_id'
        );
    }

    public function review()
    {
        return $this->hasOne(
            RestaurantReview::class,
            'order_id'
        );
    }
}
