<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreightCharge extends Model
{
    //
    protected $fillable = [
        'shipping_zone_id',
        'min_weight',
        'max_weight',
        'charge'
    ];
}
