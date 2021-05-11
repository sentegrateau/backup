<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = ['room_id','partner_id','name','description', 'status'];
    protected $dates = ['deleted_at'];
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

}
