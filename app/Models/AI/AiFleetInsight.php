<?php

namespace App\Models\AI;

use App\Models\Fleets\Fleet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiFleetInsight extends Model
{
    protected $fillable = ['fleet_id', 'insight_type', 'summary', 'recommendation', 'priority'];

    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }
}
