<?php

namespace App\Models\AI;

use App\Models\User;
use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiDiagnosis extends Model
{
    protected $table = 'ai_diagnoses';

    protected $fillable = ['vehicle_id', 'user_id', 'conversation_id', 'symptoms', 'dtc_code', 'possible_causes', 'recommended_actions', 'urgency_level', 'confidence_score'];

    protected $casts = [
        'possible_causes' => 'array',
        'recommended_actions' => 'array',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AiConversation::class, 'conversation_id');
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(AiRepairSuggestion::class, 'diagnosis_id');
    }

    public function garageRecommendations(): HasMany
    {
        return $this->hasMany(AiGarageRecommendation::class, 'diagnosis_id');
    }
}
