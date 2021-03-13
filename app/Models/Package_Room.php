<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package_Room extends Model
{
    use HasFactory;

    protected $fillable = ['pr_id','package_id','room_id','number_of_rooms'];
}
