<?php

namespace App\Models\AI;

use App\Models\Marketplace\SparePart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiPartRecommendation extends Model
{
    protected $fillable = ['diagnosis_id', 'part_id', 'compatibility_score', 'priority'];

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(AiDiagnosis::class, 'diagnosis_id');
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(SparePart::class, 'part_id');
    }
}
