<?php

namespace App\Models\Workflows;

use App\Models\Garages\VehicleDiagnosis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleFaultCode extends Model
{
    protected $fillable = ['diagnosis_id', 'code', 'description', 'severity', 'solution'];

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(VehicleDiagnosis::class, 'diagnosis_id');
    }
}
