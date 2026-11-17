<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class RestaurantPanel extends Authenticatable
{
    use Notifiable;

    protected $table = 'restaurantpanel';

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'profile_pic',
        'otp',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
