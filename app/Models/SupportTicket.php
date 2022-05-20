<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class SupportTicket extends Model
{
    protected $fillable = ['ticket_category_id', 'ticket_id', 'user_id', 'title', 'message', 'status'];

    protected $appends = ['full'];
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\TicketCategory','ticket_category_id');
    }

    public function lastComment()
    {
        return $this->hasOne('App\Models\SupportTicketComment','support_ticket_id')->where('is_hide',0)->orderBy('id','desc');
    }
    public function comments()
    {
        return $this->hasMany('App\Models\SupportTicketComment','support_ticket_id');
    }
    public function getFullAttribute()
    {
        if (!empty($this->attachment) && Storage::disk('ticket_attachment')->exists($this->attachment)) {
            return Storage::disk('ticket_attachment')->url($this->attachment);
        } else {
            return url('public/img/no-image.png');
        }
    }
}

