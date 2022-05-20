<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdminPermission extends Model
{
    public $fillable=['user_id','read_permit','module_name'];
}
