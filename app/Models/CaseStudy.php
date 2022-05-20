<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{
    protected $appends = ['shortContent', 'createdPost','postDate'];

    public function images()
    {
        return $this->hasMany('App\Models\CaseStudyImage');
    }

    public function singleImage()
    {
        return $this->hasOne('App\Models\BlogImage');
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
