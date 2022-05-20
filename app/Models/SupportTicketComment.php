<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SupportTicketComment extends Model
{
    protected $fillable = ['support_ticket_id', 'sender_id', 'receiver_id', 'message','image'];

    protected $appends = ['full'];

    public function sender()
    {
        return $this->belongsTo('App\Models\User','sender_id');
    }
    public function receiver()
    {
        return $this->belongsTo('App\Models\User','receiver_id');
    }
    public function supportTicket()
    {
        return $this->belongsTo('App\Models\SupportTicket','support_ticket_id');
    }

    public function getFullAttribute()
    {
        if (!empty($this->image) && Storage::disk('support_attachment')->exists($this->image)) {
            return Storage::disk('support_attachment')->url($this->image);
        } else {
            return url('public/img/no-image.png');
        }
    }
}

