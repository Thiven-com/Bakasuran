<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_variant_id',
        'quantity',
        'unit_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id')
            ->with([
                'product:id,title,slug',
                'attributeValues.attribute'
            ]);
    }

    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

}
