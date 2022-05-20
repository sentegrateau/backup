<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Device extends Model
{

    protected $fillable = ['partner_id', 'name', 'description', 'brand',
        'model', 'active', 'price', 'image', 'discount', 'supplier', 'status'];

    protected $dates = ['deleted_at'];

    protected $appends = ['imageUrl'];

    public function packagesRoomsDevices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Models\Package_Room', 'device_id', 'id');
    }

    public function subDevices(){
        return $this->hasMany('App\Models\DeviceFeature', 'device_id', 'id');
    }

    function getImageUrlAttribute()
    {
        if (!empty($this->image) && Storage::disk('device_uploads')->exists($this->image)) {
            $imgUrl = Storage::disk('device_uploads')->url($this->image);
            return $imgUrl;
        } else {
            return url('img/no-image-2.png');
        }
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deviceFeatureFunctions()
    {
        return $this->hasMany(DeviceFeature::class)->where('type', 'device_type');
    }

    public function deviceFeatureParams()
    {
        return $this->hasMany(DeviceFeature::class)->where('type', 'param');
    }
}
