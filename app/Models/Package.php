<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['package_id','partner_id', 'name','description', 'status'];
    protected $dates = ['deleted_at'];
    public function partners()
    {
        return $this->belongsTo('App\Partner');
    }

    /*public function rooms()
    {
        return $this->belongsToMany(Room::class,
            'package__room__device',
            'package_id',
            'room_id');
    }*/
}
