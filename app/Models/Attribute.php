<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function attributeValues()
    {
    return $this->hasMany(AttributeValue::class, 'attribute_id');
    }
    public function variants()
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'variant_attribute_values',
            'attribute_id',       // pivot.attribute_id
            'product_variant_id'  // pivot.product_variant_id
        );
    }

    // Products using this attribute (via variants)
    public function products()
    {
        return $this->variants()
            ->with('product') // eager load
            ->get()
            ->pluck('product')
            ->unique('id');
    }
        public function productCount(): int
    {
        return \DB::table('products')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->join('variant_attribute_values', 'product_variants.id', '=', 'variant_attribute_values.product_variant_id')
            ->where('variant_attribute_values.attribute_id', $this->id)
            ->distinct('products.id')
            ->count('products.id');
    }

}
