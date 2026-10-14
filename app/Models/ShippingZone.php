<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    //

    protected $fillable = [
        'zone_name',
        'shipping_method',
        'regions',
        'free_shipping'
    ];
    public function charges()
    {
        return $this->hasMany(FreightCharge::class);
    }
}
