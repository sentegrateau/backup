<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{

    use HasFactory;

    protected $fillable = ['room_id','partner_id','name','description', 'package_id'];

    public function packages()
    {
        return $this->belongsToMany(Package::class,
            'package__rooms',
            'room_id',
            'package_id');
    }

    public function package()
    {
        return $this->belongsTo('App\Package');
    }

    public function partners()
    {
        return $this->belongsTo('App\Partner');
    }

}
