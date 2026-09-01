<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminAuditLog extends Model
{
    protected $fillable = ['administrator_id', 'action', 'entity_type', 'entity_id', 'ip_address', 'device_info'];

    public function administrator(): BelongsTo
    {
        return $this->belongsTo(Administrator::class);
    }
}
