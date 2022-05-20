<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Room extends Model
{

    use SoftDeletes;

    protected $fillable = ['room_id', 'partner_id', 'name', 'description', 'status', 'home_owner'];
    protected $dates = ['deleted_at'];

    protected $appends = ['imageUrl', 'customName'];

    /*public function packages()
    {
        return $this->belongsToMany(Package::class,
            'package__rooms',
            'room_id',
            'package_id');
    }

    public function package()
    {
        return $this->belongsTo('App\Package');
    }*/

    public function partners()
    {
        return $this->belongsTo('App\Partner');
    }

    function getImageUrlAttribute()
    {
        if (!empty($this->img) && Storage::disk('room_uploads')->exists($this->img)) {
            $imgUrl = Storage::disk('room_uploads')->url($this->img);
            return $imgUrl;
        } else {
            return url('public/img/no-image-2.png');
        }
    }

    function getCustomNameAttribute()
    {
        return $this->name;
    }

    public function homeOwners()
    {
        return $this->belongsToMany(HomeOwner::class,
            'home_owner_rooms',
            'room_id',
            'home_owner_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function packageRoom()
    {
        return $this->hasMany(Package_Room::class);
    }

}
