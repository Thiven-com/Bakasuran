<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
    // public function productCount(): int
    // {
    //     return \DB::table('products')
    //         ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
    //         ->join('variant_attribute_values', 'product_variants.id', '=', 'variant_attribute_values.product_variant_id')
    //         ->where('variant_attribute_values.attribute_value_id', $this->id)
    //         ->distinct('products.id')
    //         ->count('products.id');
    // }

    protected $fillable = [
        'name',
        'attribute_id',
        'slug'
    ];
}
