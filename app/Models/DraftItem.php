<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DraftItem extends Model
{
    use HasFactory;

    protected $guarded;

    public function order(): BelongsTo
    {
        return $this->belongsTo(Draft::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    public function device():BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
