<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $fillable = [
        'order_id',
        'user_id',
        'amount',
        'currency',
        'status',
        'method',
        'provider',
        'reference_no',
        'provider_order_id',
        'provider_payment_id',
        'provider_signature',
        'paid_at',
        'meta',
    ];
    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }    //
}
