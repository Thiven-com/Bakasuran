<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    //
    protected $fillable = [
        'user_id',
        'module',
        'action',
        'record_id',
        'description',
        'changes'
    ];
    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'record_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'record_id');
    }
    public function productvariant()
    {
        return $this->belongsTo(ProductVariant::class, 'record_id');
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'record_id');
    }
}
