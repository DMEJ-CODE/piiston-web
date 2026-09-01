<?php

namespace App\Models\Garages;

use App\Models\User;
use App\Models\Workflows\DiagnosticMeasurement;
use App\Models\Workflows\VehicleFaultCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleDiagnosis extends Model
{
    protected $fillable = [
        'repair_order_id', 'mechanic_id', 'symptoms', 'dtc_codes', 'detected_problem',
        'root_cause', 'solution', 'recommendation', 'ai_hypotheses', 'severity', 'is_ai_generated',
    ];

    protected $casts = [
        'dtc_codes' => 'array',
        'ai_hypotheses' => 'array',
        'is_ai_generated' => 'boolean',
    ];

    public function media()
    {
        return $this->morphMany(GarageMedia::class, 'mediable');
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(DiagnosticMeasurement::class, 'diagnosis_id');
    }

    public function faultCodes(): HasMany
    {
        return $this->hasMany(VehicleFaultCode::class, 'diagnosis_id');
    }

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }
}
