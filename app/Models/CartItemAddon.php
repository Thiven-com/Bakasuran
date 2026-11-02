<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItemAddon extends Model
{
    protected $fillable = [
        'cart_item_id',
        'addon_option_id',
        'addon_name',
        'unit_price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class, 'cart_item_id');
    }

    public function addonOption()
    {
        return $this->belongsTo(AddonOption::class, 'addon_option_id');
    }
}