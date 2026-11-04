<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    protected $table = 'product_ratings';

    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillable = [
        'customer_id',
        'product_id',
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

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
