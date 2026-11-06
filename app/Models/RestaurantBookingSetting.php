<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantBookingSetting extends Model
{
    protected $fillable = [
        'restaurant_id',
        'slot_duration',
        'booking_duration',
        'cleaning_duration',
        'min_notice_minutes',
        'max_advance_days',
        'min_guests',
        'max_guests',
        'is_booking_enabled',
    ];

    protected function casts(): array
    {
        return [
            'restaurant_id' => 'integer',
            'slot_duration' => 'integer',
            'max_advance_days' => 'integer',
            'min_guests' => 'integer',
            'max_guests' => 'integer',
            'is_booking_enabled' => 'boolean',
            'booking_duration' => 'integer',
            'cleaning_duration' => 'integer',
            'min_notice_minutes' => 'integer',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
