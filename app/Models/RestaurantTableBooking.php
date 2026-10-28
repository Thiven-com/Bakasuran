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
        'special_requests',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date:Y-m-d',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function restaurantTable(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class);
    }
}
