<?php

namespace App\Models\Messaging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SharedLocation extends Model
{
    protected $fillable = ['message_id', 'latitude', 'longitude', 'address'];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}
