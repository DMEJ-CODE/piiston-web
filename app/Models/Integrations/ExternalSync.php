<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalSync extends Model
{
    protected $fillable = ['integration_id', 'entity_type', 'entity_id', 'sync_status', 'last_sync_at'];

    protected $casts = [
        'last_sync_at' => 'datetime',
    ];

    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }
}
