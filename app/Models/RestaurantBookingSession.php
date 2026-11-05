<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantBookingSession extends Model
{
    protected $fillable = [
        'restaurant_id',
        'session_name',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'restaurant_id' => 'integer',
            'day_of_week' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(
            RestaurantTableBooking::class,
            'booking_session_id'
        );
    }
}