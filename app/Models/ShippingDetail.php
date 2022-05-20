<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingDetail extends Model
{
    protected $columns = ['id', 'user_id',
        'same_add',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_email',
        'shipping_address',
        'shipping_address_2',
        'shipping_country',
        'shipping_state',
        'shipping_zip',
        'shipping_city',
        'shipping_phone',
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_address',
        'billing_address_2',
        'billing_country',
        'billing_state',
        'billing_zip',
        'billing_phone',
        'billing_city'
    ];

    protected $fillable = ['id', 'user_id',
        'same_add',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_email',
        'shipping_address',
        'shipping_address_2',
        'shipping_country',
        'shipping_state',
        'shipping_zip',
        'shipping_phone',
        'shipping_city',

        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_address',
        'billing_address_2',
        'billing_country',
        'billing_state',
        'billing_zip',
        'billing_phone',
        'billing_city'
    ];

    //protected $appends = ['ship_country', 'bill_country'];

    public function scopeExclude($query, $value = array())
    {
        return $query->select(array_diff($this->columns, (array)$value));
    }

    /*public function getShipCountryAttribute()
    {
        if (!empty($this->shipping_country))
            return Country::where('id', '=', $this->shipping_country)->first()->name;

        return null;
    }

    public function getBillCountryAttribute()
    {
        if (!empty($this->billing_country))
            return Country::where('id', '=', $this->billing_country)->first()->name;

        return null;
    }*/

}
