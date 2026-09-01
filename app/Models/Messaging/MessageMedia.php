<?php

namespace App\Models\Messaging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageMedia extends Model
{
    protected $table = 'message_media';

    protected $fillable = [
        'message_id', 'file_type', 'file_url', 'file_name',
        'file_size', 'duration', 'thumbnail',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }
}
