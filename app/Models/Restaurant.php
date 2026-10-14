<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'cover_image',
        'cuisine_type',
        'address',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'phone',
        'email',
        'opening_time',
        'closing_time',
        'average_rating',
        'delivery_time',
        'delivery_fee',
        'minimum_order',
        'is_active',
        'is_open',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'average_rating' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'minimum_order' => 'decimal:2',
            'is_active' => 'boolean',
            'is_open' => 'boolean',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'restaurant_id');
    }
}
