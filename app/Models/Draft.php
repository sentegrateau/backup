<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Draft extends Model
{
    use Notifiable, SoftDeletes;

    protected $fillable = ['user_id', 'partner_id', 'type', 'title', 'category', 'quotation_no', 'total_amount', 'validity'];

    protected $dates = ['deleted_at'];

	protected $appends = ['quot_name'];


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

	 protected function getQuotNameAttribute()
    {
		//$trans = array("-" => "", ":" => ""," "=>"");

		//$created_at=strtr($this->created_at, $trans);
        return 'Quotation-'.$this->id.'-'.$this->created_at->format('Ymd');
    }
}
