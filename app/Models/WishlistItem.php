<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id')
            ->with(['product:id,title,slug,short_description,description,category_id','product.category:id,title,slug']);
    }
}
