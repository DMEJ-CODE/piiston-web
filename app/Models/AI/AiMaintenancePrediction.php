<?php

namespace App\Models\AI;

use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiMaintenancePrediction extends Model
{
    protected $fillable = ['vehicle_id', 'prediction_type', 'predicted_date', 'predicted_mileage', 'confidence_score'];

    protected $casts = [
        'predicted_date' => 'date',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
