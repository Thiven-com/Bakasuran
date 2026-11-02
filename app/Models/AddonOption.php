<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonOption extends Model
{
    protected $fillable = [
        'addon_group_id',
        'name',
        'price',
        'track_stock',
        'stock',
        'low_stock_alert',
        'is_available',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'track_stock' => 'boolean',
        'stock' => 'integer',
        'low_stock_alert' => 'integer',
        'is_available' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function group()
    {
        return $this->belongsTo(AddonGroup::class, 'addon_group_id');
    }
}
