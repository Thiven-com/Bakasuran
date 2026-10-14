<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use LogsActivity;

    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'brand_id');
    }
}
