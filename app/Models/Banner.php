<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    protected $appends = ['full'];

    public function getFullAttribute()
    {
        return Storage::disk('banner_uploads')->url($this->image);
    }
}
