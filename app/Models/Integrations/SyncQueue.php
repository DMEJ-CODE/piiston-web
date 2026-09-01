<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncQueue extends Model
{
    protected $table = 'sync_queue';

    protected $fillable = ['integration_id', 'entity', 'operation', 'status', 'retry_count'];

    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }
}
