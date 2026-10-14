<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSaleProduct extends Model
{
    protected $fillable = [
        'flash_sale_id', 'product_variant_id',
        'discount_type', 'discount_value',
    ];

    public function flashSale()
    {
        return $this->belongsTo(FlashSale::class, 'flash_sale_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function getEffectivePrice()
    {
        $variant = $this->variant;
        if (!$variant) return null;

        if ($this->discount_type === 'fixed') {
            return max(0, $variant->price - $this->discount_value);
        }
        if ($this->discount_type === 'percent') {
            return max(0, $variant->price - ($variant->price * $this->discount_value / 100));
        }
        return $variant->price;
    }
}
