<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantAttributeValue extends Model
{
    protected $fillable = [
        'product_variant_id',
        'attribute_id',
        'attribute_value_id',
    ];
public function attribute()
{
    return $this->belongsTo(\App\Models\Attribute::class, 'attribute_id');
}

public function value()
{
    return $this->belongsTo(\App\Models\AttributeValue::class, 'attribute_value_id');
}
}
