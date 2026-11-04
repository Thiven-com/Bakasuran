<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantReview extends Model
{
    protected $fillable = [
        'customer_id',
        'restaurant_id',
        'order_id',
        'rating',
        'review',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
