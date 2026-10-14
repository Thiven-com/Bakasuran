<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class OrderNote extends Model
{
    //
    use LogsActivity;

    protected $fillable = [
        'order_id',
        'type',
        'note',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
