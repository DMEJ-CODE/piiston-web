<?php

namespace App\Models\Messaging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatContext extends Model
{
    protected $fillable = ['conversation_id', 'context_type', 'context_id'];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function context()
    {
        return $this->morphTo();
    }
}
