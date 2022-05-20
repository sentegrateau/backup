<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SceneDeviceType extends Model
{
    public function AdminDeviceTypeRelation(){
        return $this->hasMany('App\Models\SceneDeviceRelation', 'scene_device_type_id', 'id')->where('sub_device_id',0)->where('user_id',0);
    }

    public function AdminSubDeviceTypeRelation(){
        return $this->hasMany('App\Models\SceneDeviceRelation', 'scene_device_type_id', 'id')->where('sub_device_id','>',0)->where('user_id',0);
    }
}
