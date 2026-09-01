<?php

namespace App\Models\AI;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUsageLog extends Model
{
    protected $fillable = ['user_id', 'assistant_id', 'model_id', 'tokens_used', 'estimated_cost', 'execution_time_ms'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
