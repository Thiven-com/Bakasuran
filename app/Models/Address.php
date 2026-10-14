<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'name',
        'customer_id',
        'gst',
        'address',
        'address_2',
        'city',
        'pincode',
        'landmark',
        'email',
        'mobile',
        'alternate_mobile',
        'state_id',
    ];
    public function toSnapshot(): array
    {
        return [
            'name'      => $this->name,
            'gst'       => $this->gst,
            'address'   => $this->address,
            'address_2' => $this->address_2,
            'city'      => $this->city,
            'pincode'   => $this->pincode,
            'landmark'  => $this->landmark,
            'email'     => $this->email,
            'mobile'    => $this->mobile,
            'alternate_mobile' => $this->alternate_mobile,
            'state_id'  => $this->state_id,
            'state'     => $this->state,
        ];
    }
}
