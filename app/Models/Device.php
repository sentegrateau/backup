<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = ['partner_id','name','description','brand',
    'model','active','price','image', 'discount', 'supplier', 'status'];

    protected $dates = ['deleted_at'];

    public function packagesRoomsDevices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Package_Room','device_id','id');
    }
}
