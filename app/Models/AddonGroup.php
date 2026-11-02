<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonGroup extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'selection_type',
        'is_required',
        'min_select',
        'max_select',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'min_select' => 'integer',
        'max_select' => 'integer',
        'sort_order' => 'integer',
    ];

    public function options()
    {
        return $this->hasMany(AddonOption::class, 'addon_group_id')
            ->orderBy('sort_order');
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_addon_groups',
            'addon_group_id',
            'product_id'
        )->withPivot('sort_order')->withTimestamps();
    }
}
