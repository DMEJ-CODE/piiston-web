<?php

namespace App\Models\Maps;

use App\Models\Garages\GarageCompany;
use App\Models\Mechanics\MechanicProfile;
use App\Models\Workflows\EmergencyRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoadsideAssistance extends Model
{
    protected $table = 'roadside_assistances';

    protected $fillable = ['emergency_request_id', 'mechanic_id', 'garage_id', 'estimated_arrival_at', 'completed_at'];

    protected $casts = [
        'estimated_arrival_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(EmergencyRequest::class, 'emergency_request_id');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(MechanicProfile::class);
    }

    public function garage(): BelongsTo
    {
        return $this->belongsTo(GarageCompany::class);
    }
}
