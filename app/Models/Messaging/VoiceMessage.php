<?php

namespace App\Models\Messaging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoiceMessage extends Model
{
    protected $fillable = ['message_id', 'audio_url', 'duration', 'waveform_data'];

    protected $casts = [
        'waveform_data' => 'array',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}
