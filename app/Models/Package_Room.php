<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Package_Room extends Model
{
    protected $table = 'package__room__device';
    protected $fillable = ['package_id', 'room_id', 'device_id', 'min_qty', 'max_qty'];


    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }
}
