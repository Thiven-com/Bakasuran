<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = [
        'title', 'banner', 'description',
        'starts_at', 'ends_at', 'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(FlashSaleProduct::class, 'flash_sale_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }
}
