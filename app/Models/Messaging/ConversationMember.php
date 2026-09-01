<?php

namespace App\Models\Messaging;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationMember extends Model
{
    protected $fillable = [
        'conversation_id', 'user_id', 'role', 'joined_at',
        'last_seen_message_id', 'is_admin', 'notification_status',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lastSeenMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'last_seen_message_id');
    }
}
