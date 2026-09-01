<?php

namespace App\Models\Messaging;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    const TYPE_TEXT = 'TEXT';

    const TYPE_IMAGE = 'IMAGE';

    const TYPE_VIDEO = 'VIDEO';

    const TYPE_AUDIO = 'AUDIO';

    const TYPE_DOCUMENT = 'DOCUMENT';

    const TYPE_LOCATION = 'LOCATION';

    const TYPE_SYSTEM = 'SYSTEM';

    protected $fillable = [
        'conversation_id', 'sender_id', 'message_type', 'content',
        'reply_message_id', 'sent_at', 'edited_at', 'deleted_at', 'status',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'edited_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_message_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(MessageMedia::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(MessageReaction::class);
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(MessageStatus::class);
    }
}
