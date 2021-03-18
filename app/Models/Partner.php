<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = ['name','email','password','phone','company','active'];

    public function packages()
    {
        return $this->hasMany('App\Package');
    }

    public function rooms()
    {
        return $this->hasMany('App\Room');
    }
}
