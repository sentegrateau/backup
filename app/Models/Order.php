<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    protected $fillable = ['user_id', 'partner_id', 'type', 'title', 'amount', 'shipping_info', 'stripe_order_id', 'ship_typ', 'ship_amt','draft_id','discount','coupon_code'];

    protected $dates = ['deleted_at'];

    protected $appends = ['order_number','bank_img_url'];

    public function items()
    {
        return $this->hasMany('App\Models\OrderItem', 'order_id');
    }

    public function draft()
    {
        return $this->hasMany('App\Models\Draft', 'id','draft_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function partner()
    {
        return $this->belongsTo('App\Models\Partner');
    }

    public function getOrderNumberAttribute()
    {
        // use two times to decrypt value (((0x0000FFFF & $n) << 16) + ((0xFFFF0000 & $n) >> 16))
        $n = $this->id;
        return (((0x0000FFFF & $n) << 16) + ((0xFFFF0000 & $n) >> 16));
    }

    public function getBankImgUrlAttribute()
    {
        // use two times to decrypt value (((0x0000FFFF & $n) << 16) + ((0xFFFF0000 & $n) >> 16))
        //order_bank_uploads
        if (!empty($this->bank_img) && Storage::disk('order_bank_uploads')->exists($this->bank_img)) {
            return Storage::disk('order_bank_uploads')->url($this->bank_img);
        } else {
            return null;
        }
    }
}
