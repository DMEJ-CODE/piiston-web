<?php

namespace App\Models\Marketplace;

use App\Models\Vehicles\VehicleBrand;
use App\Models\Vehicles\VehicleGeneration;
use App\Models\Vehicles\VehicleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartVehicleCompatibility extends Model
{
    protected $table = 'part_vehicle_compatibilities';

    protected $fillable = [
        'part_id', 'vehicle_brand_id', 'vehicle_model_id',
        'generation_id', 'year_from', 'year_to',
        'engine_type', 'notes',
    ];

    public function part(): BelongsTo
    {
        return $this->belongsTo(SparePart::class, 'part_id');
    }

    public function vehicleBrand(): BelongsTo
    {
        return $this->belongsTo(VehicleBrand::class, 'vehicle_brand_id');
    }

    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    public function generation(): BelongsTo
    {
        return $this->belongsTo(VehicleGeneration::class);
    }
}
