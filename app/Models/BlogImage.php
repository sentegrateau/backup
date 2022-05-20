<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Image;

class BlogImage extends Model
{
    protected $appends = ['medium', 'large', 'thumb', 'full'];

    public function createThumbs($image, $imageName)
    {
        Image::make($image)->resize(150, 150)
            ->save(public_path('blog/thumbs/150x150-' . $imageName));
        Image::make($image)->resize(320, 240)
            ->save(public_path('blog/thumbs/320x240-' . $imageName));
        Image::make($image)->resize(800, 640)
            ->save(public_path('blog/thumbs/800x640-' . $imageName));
    }

    public function getMediumAttribute()
    {
        if (!empty($this->image && Storage::disk('blog_uploads')->exists('/thumbs/320x240-' . $this->image))) {
            return Storage::disk('blog_uploads')->url('/thumbs/320x240-' . $this->image);
        } else {
            return url('img/no-image.png');
        }
    }

    public function getLargeAttribute()
    {
        if (!empty($this->image) && Storage::disk('blog_uploads')->exists('/thumbs/800x640-' . $this->image)) {
            return Storage::disk('blog_uploads')->url('/thumbs/800x640-' . $this->image);
        } else {
            return url('img/no-image.png');
        }
    }

    public function getThumbAttribute()
    {
        if (!empty($this->image) && Storage::disk('blog_uploads')->exists('/thumbs/150x150-' . $this->image)) {
            return Storage::disk('blog_uploads')->url('/thumbs/150x150-' . $this->image);
        } else {
            return url('img/no-image.png');
        }
    }

    public function getFullAttribute()
    {
        if (!empty($this->image && Storage::disk('blog_uploads')->exists($this->image))) {
            return Storage::disk('blog_uploads')->url($this->image);
        } else {
            return url('img/no-image.png');
        }
    }
}
