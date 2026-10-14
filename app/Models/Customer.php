<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'name',
        'mobile',
        'otp',
        'mobile_verified',
        'mobile_verified_at',
        'email',
        'email_verified',
        'email_verified_at',
        'username',
        'address_id',
        'wallet',
        'profile_pic',
        'password',
        'firebase_token',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'otp',
        'remember_token',
    ];

    protected $casts = [
        'mobile_verified_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'wallet' => 'double',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function carts()
    {
        return $this->hasMany(CartItem::class, 'user_id');
    }
}
