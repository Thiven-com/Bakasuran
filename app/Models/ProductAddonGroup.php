<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAddonGroup extends Model
{
    protected $fillable = [
        'product_id',
        'addon_group_id',
        'sort_order',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'addon_group_id' => 'integer',
        'sort_order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function addonGroup()
    {
        return $this->belongsTo(AddonGroup::class, 'addon_group_id');
    }
}