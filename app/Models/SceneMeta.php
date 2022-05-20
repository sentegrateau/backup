<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SceneMeta extends Model
{
    public function metaValue(){
        return $this->hasMany('App\Models\SceneMetaValue', 'scene_meta_id', 'id')->where('user_id',0);
    }
}
