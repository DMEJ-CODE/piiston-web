<?php

namespace App\Models\Messaging;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    const TYPE_PRIVATE = 'PRIVATE';

    const TYPE_GROUP = 'GROUP';

    const TYPE_BUSINESS = 'BUSINESS';

    protected $fillable = ['conversation_type', 'name', 'avatar', 'description', 'created_by', 'last_message_id'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_members')
            ->withPivot(['role', 'joined_at', 'last_seen_message_id', 'is_admin', 'notification_status'])
            ->withTimestamps();
    }

    public function members(): HasMany
    {
        return $this->hasMany(ConversationMember::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    public function contexts(): HasMany
    {
        return $this->hasMany(ChatContext::class);
    }
}
