<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantTableBooking extends Model
{
    protected $fillable = [
        'booking_number',
        'restaurant_id',
        'restaurant_table_id',
        'customer_id',
        'customer_name',
        'customer_mobile',
        'customer_email',
        'booking_date',
        'booking_time',
        'end_time',
        'guest_count',
        'seating_type',
        'occasion',
        'special_requests',
        'status',
        'confirmed_at',
        'cancelled_at',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date:Y-m-d',
            'guest_count' => 'integer',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function restaurantTable(): BelongsTo
    {
        return $this->belongsTo(
            RestaurantTable::class,
            'restaurant_table_id'
        );
    }

    public function customer(): BelongsTo
{
    return $this->belongsTo(
        Customer::class,
        'customer_id'
    );
}
}
