<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Draft extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'partner_id', 'type', 'title', 'category', 'quotation_no', 'total_amount', 'validity'];

    protected $dates = ['deleted_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function partner(): BelongsTo
    {
        return $this->belongsTo('App\Models\User', 'partner_id', 'id');
    }

    public function items(): HasMany
    {
        return $this->hasMany('App\Models\DraftItem');
    }
}
