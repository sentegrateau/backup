<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Scene extends Model
{
    public function scene_device_type(){
        return $this->hasMany('App\Models\SceneDeviceType', 'scene_id', 'id');
    }

    public function sceneMetas(){
        return $this->hasMany('App\Models\SceneMeta', 'scene_id', 'id');
    }
}
