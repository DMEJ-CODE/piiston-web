<?php

namespace App\Models\AI;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiMemory extends Model
{
    protected $fillable = ['user_id', 'memory_type', 'content', 'importance', 'expires_at'];

    protected function casts(): array
    {
        return [
            'importance' => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
