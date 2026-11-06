<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantTable extends Model
{
    protected $fillable = [
        'restaurant_id',
        'table_name',
        'table_number',
        'seating_capacity',
        'seating_type',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'restaurant_id' => 'integer',
            'seating_capacity' => 'integer',
            'status' => 'integer',
        ];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function bookings()
    {
        return $this->hasMany(
            RestaurantTableBooking::class,
            'restaurant_table_id'
        );
    }
}
