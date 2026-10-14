<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity;
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function variant()
    {
        return $this->hasOne(ProductVariant::class);
    }

    public function subcategories()
    {
        return $this->belongsToMany(Category::class, 'category_products', 'product_id', 'category_id');
    }
    public function media()
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort_order');
    }

    public function primaryMedia()
    {
        return $this->hasOne(ProductMedia::class)->where('is_primary', true)->ofMany('id', 'min');
    }
}
