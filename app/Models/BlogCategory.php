<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BlogCategory extends Model
{
    public $fillable = ['name', 'slug', 'featured', 'status'];

    public function blogs()
    {
        return $this->hasMany('App\Models\Blog','blog_category_id','id');
    }
}
