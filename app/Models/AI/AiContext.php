<?php

namespace App\Models\AI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiContext extends Model
{
    protected $fillable = ['conversation_id', 'entity_type', 'entity_id', 'summary'];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AiConversation::class);
    }
}
