<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $fillable = [
        'delivery_partner_id',
        'amount',
        'settlement_status',
        'settled_by',
        'reference_number',
        'notes',
        'settled_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'settled_at' => 'datetime',
    ];

    public function deliveryPartner()
    {
        return $this->belongsTo(DeliveryPartner::class);
    }
}
