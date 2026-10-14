<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMedia extends Model
{
    
    protected $fillable = [
        'product_id','type','mime','url','thumbnail_url','sort_order','is_primary',
        'width','height','duration','meta'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'meta'       => 'array',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Helpers
    public function isImage(): bool { return $this->type === 'image' || ($this->mime && str_starts_with($this->mime, 'image/')); }
    public function isVideo(): bool { return $this->type === 'video' || ($this->mime && str_starts_with($this->mime, 'video/')); }
    public function isGif(): bool   { return $this->type === 'gif'   || $this->mime === 'image/gif'; }
}
