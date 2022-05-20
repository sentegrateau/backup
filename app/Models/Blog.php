<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{

    protected $appends = ['shortContent', 'createdPost','postDate'];

    public function blogImages()
    {
        return $this->hasMany('App\Models\BlogImage');
    }

    public function blogTags()
    {
        return $this->hasMany('App\Models\BlogTag');
    }

    public function blogSingleImages()
    {
        return $this->hasOne('App\Models\BlogImage');
    }

    public function blogComments()
    {
        return $this->hasMany('App\Models\BlogComment');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function blogcategory()
    {
        return $this->belongsTo('App\Models\BlogCategory', 'blog_category_id', 'id');
    }

    public function getShortContentAttribute()
    {
        return substr(strip_tags($this->content), 0, 100) . '...';
    }

    public function getCreatedPostAttribute()
    {
        return $this->created_at->diffForHumans();
    }
    public function getPostDateAttribute()
    {
        return date('F d, Y',strtotime($this->created_at));
    }
}
