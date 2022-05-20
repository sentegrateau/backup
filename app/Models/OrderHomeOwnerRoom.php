<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHomeOwnerRoom extends Model
{

    protected $fillable = ['room_id', 'home_owner_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function homeOwner()
    {
        return $this->belongsTo(HomeOwner::class, 'home_owner_id', 'id');
    }
}
