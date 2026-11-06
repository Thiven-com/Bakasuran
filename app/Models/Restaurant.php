<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

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

        // Restaurant display fields
        'price_for_two',
        'discount_percentage',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'average_rating' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'minimum_order' => 'decimal:2',
            'price_for_two' => 'decimal:2',
            'discount_percentage' => 'integer',
            'is_active' => 'boolean',
            'is_open' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'restaurant_id');
    }

    public function tables()
    {
        return $this->hasMany(RestaurantTable::class, 'restaurant_id');
    }

    public function bookings()
    {
        return $this->hasMany(RestaurantTableBooking::class, 'restaurant_id');
    }

    public function bookingSettings()
    {
        return $this->hasOne(RestaurantBookingSetting::class, 'restaurant_id');
    }

    public function bookingHours(): HasMany
    {
        return $this->hasMany(RestaurantBookingHour::class, 'restaurant_id');
    }

    public function bookingSessions(): HasMany
    {
        return $this->hasMany(RestaurantBookingSession::class, 'restaurant_id');
    }
}
