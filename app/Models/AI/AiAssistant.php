<?php

namespace App\Models\AI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiAssistant extends Model
{
    protected $fillable = ['name', 'assistant_type', 'description', 'default_model_id', 'language_support', 'status'];

    protected $casts = [
        'language_support' => 'array',
    ];

    public function defaultModel(): BelongsTo
    {
        return $this->belongsTo(AiModel::class, 'default_model_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(AiConversation::class, 'assistant_id');
    }
}
