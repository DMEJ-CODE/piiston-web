<?php

namespace App\Models\AI;

use App\Models\Garages\GarageCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiGarageRecommendation extends Model
{
    protected $fillable = ['diagnosis_id', 'garage_id', 'recommendation_score', 'estimated_distance_km', 'reason'];

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(AiDiagnosis::class, 'diagnosis_id');
    }

    public function garage(): BelongsTo
    {
        return $this->belongsTo(GarageCompany::class, 'garage_id');
    }
}
