<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;


class Order extends Model
{
    use LogsActivity;

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];
    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'order_id');
    }
    public function items()
    {
        return $this->hasMany(\App\Models\OrderItem::class, 'order_id');
    }
    public function shipments()
    {
        return $this->hasMany(\App\Models\OrderShipment::class, 'order_id');
    }
    public function user()
    {
        // if your customers are stored in users table and FK is 'user_id'
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
    }

    public function notes()
    {
        return $this->hasMany(OrderNote::class)->latest();
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
    

    protected $fillable = [
        'customer_id',
        'order_id',
        'invoice_id',
        'subtotal',
        'grand_total',
        'status',
        'payment_status',
        'shipping_address',
        'billing_address',

        // Shipment fields
        'carrier',
        'awb',
        'courier_shipment_id',
        'shipment_status',
        'shipment_message',
        'shipment_response',
    ];
}
