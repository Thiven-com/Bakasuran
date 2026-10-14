<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;


class ProductVariant extends Model
{
    use LogsActivity;
    protected $fillable = [
        'product_id',
        'category_id',
        'brand_id',
        'product_slug',
        'sku',
        'actual_price',
        'price',
        'stock',
        'low_stock_alert',
        'product_min_order',
        'product_max_order',
        'preorder',
        'preorder_stock',
        'image',
        'status',
        'weight',
        'length',
        'width',
        'height',
    ];

    public function unit()
    {
        return $this->belongsTo(Attribute::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class,'');
    }
    public function attributeValues()
    {
        // Pivot: variant_attribute_values
        // Columns: product_variant_id, attribute_id, attribute_value_id
        return $this->belongsToMany(
            AttributeValue::class,
            'variant_attribute_values',
            'product_variant_id',
            'attribute_value_id'
        )
            ->withPivot('attribute_id')
            ->withTimestamps();
    }
    public function media()
    {
        return $this->hasMany(\App\Models\ProductMedia::class, 'product_variant_id');
    }
    public function attributeMappings()
    {
        return $this->hasMany(\App\Models\VariantAttributeValue::class, 'product_variant_id')
            ->with(['attribute', 'value']);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
