<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //
    use LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'discount',
        'minimum_purchase',
        'limit',
        'expiry_date',
        'status',
    ];
}
