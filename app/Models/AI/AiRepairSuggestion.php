<?php

namespace App\Models\AI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiRepairSuggestion extends Model
{
    protected $fillable = ['diagnosis_id', 'repair_type', 'estimated_duration_minutes', 'estimated_cost_min', 'estimated_cost_max', 'confidence_score'];

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(AiDiagnosis::class, 'diagnosis_id');
    }
}
