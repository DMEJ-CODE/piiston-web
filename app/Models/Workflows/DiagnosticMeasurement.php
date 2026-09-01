<?php

namespace App\Models\Workflows;

use App\Models\Garages\VehicleDiagnosis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiagnosticMeasurement extends Model
{
    protected $fillable = ['diagnosis_id', 'parameter', 'value', 'unit', 'reference_value', 'status'];

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(VehicleDiagnosis::class, 'diagnosis_id');
    }
}
