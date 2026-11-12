<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class DeliveryPartner extends Model
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $table = 'delivery_partners';

    protected $fillable = [
        'partner_code',
        'name',
        'email',
        'mobile',
        'otp',
        'alternate_mobile',
        'profile_image',
        'date_of_birth',
        'gender',

        // Address
        'address_line_1',
        'address_line_2',
        'landmark',
        'area',
        'city',
        'district',
        'state',
        'country',
        'pincode',

        // Location
        'latitude',
        'longitude',

        // Vehicle
        'vehicle_type',
        'vehicle_number',
        'vehicle_model',

        // Documents
        'aadhar_number',
        'pan_number',
        'driving_license_number',
        'driving_license_image',
        'aadhar_front_image',
        'aadhar_back_image',
        'pan_image',
        'vehicle_rc_image',

        // Bank
        'bank_name',
        'account_holder_name',
        'account_number',
        'ifsc_code',
        'upi_id',

        // Account
        'password',
        'is_verified',
        'verification_status',
        'is_available',
        'status',
        'rejection_reason',

        // Login
        'last_login_at',
        'device_token',

        'notes',
    ];

    protected $hidden = [
        'password',
        'aadhar_number',
        'pan_number',
        'account_number',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_verified' => 'boolean',
            'is_available' => 'boolean',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }
}
