<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TicketCategory extends Model
{
    public $fillable = ['name', 'slug', 'featured', 'status'];
}
