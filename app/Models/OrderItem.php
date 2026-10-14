<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use LogsActivity;

    protected $fillable = [
        'order_id',
        'product_variant_id',
        'seller_id',
        'product_title',
        'sku',
        'unit_price',
        'quantity',
        'weight',
        'subtotal',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
