<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminBroadcast extends Model
{
    protected $fillable = ['created_by', 'title', 'message', 'target_roles', 'channels', 'scheduled_at', 'status'];

    protected $casts = [
        'target_roles' => 'array',
        'channels' => 'array',
        'scheduled_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Administrator::class, 'created_by');
    }
}
