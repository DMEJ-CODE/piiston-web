<?php

namespace App\Models\Administration;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModerationCase extends Model
{
    protected $fillable = ['reported_entity', 'entity_id', 'reported_by', 'reason', 'description', 'priority', 'status', 'assigned_admin_id'];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Administrator::class, 'assigned_admin_id');
    }
}
