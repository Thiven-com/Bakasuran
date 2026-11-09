<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];


    public function restaurants()
    {
        return $this->belongsToMany(
            Restaurant::class,
            'restaurant_restaurant_type'
        )->withTimestamps();
    }
}
