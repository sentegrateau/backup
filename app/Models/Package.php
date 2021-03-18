<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['package_id','partner_id', 'name','description'];

    public function partners()
    {
        return $this->belongsTo('App\Partner');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class,
            'package__rooms',
            'package_id',
            'room_id');
    }
}
