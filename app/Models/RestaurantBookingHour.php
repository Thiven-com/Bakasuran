<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestaurantBookingHour extends Model
{
    protected $fillable = [
        'restaurant_id',
        'day_of_week',
        'opening_time',
        'closing_time',
        'is_closed',
    ];

    protected function casts(): array
    {
        return [
            'restaurant_id' => 'integer',
            'day_of_week' => 'integer',
            'is_closed' => 'boolean',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
