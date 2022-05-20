<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class DeviceParameter extends Model
{
    public $fillable = ['feature', 'type'];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }


}
