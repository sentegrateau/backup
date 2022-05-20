<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use  SoftDeletes;

    protected $fillable = ['package_id', 'partner_id', 'name', 'description', 'status', 'order'];

    protected $dates = ['deleted_at'];

    public function partners()
    {
        return $this->belongsTo('App\Partner');
    }

    /*public function rooms()
    {
        return $this->belongsToMany(Room::class,
            'package__room__device',
            'package_id',
            'room_id');
    }*/

    public function devices()
    {
        return $this->belongsToMany(Device::class,
            'package__room__device',
            'package_id',
            'device_id');
    }
}
