<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class HomeOwner extends Model
{

    use SoftDeletes;

    protected $fillable = ['name', 'description', 'status', 'room_id'];
    protected $dates = ['deleted_at'];

    protected $appends = ['imageUrl'];


    public function room()
    {
        return $this->belongsTo(Room::class);
    }


    function getImageUrlAttribute()
    {
        if (!empty($this->img) && Storage::disk('home_owner_uploads')->exists($this->img)) {
            $imgUrl = Storage::disk('home_owner_uploads')->url($this->img);
            return $imgUrl;
        } else {
            return url('public/img/no-image-2.png');
        }
    }

}
