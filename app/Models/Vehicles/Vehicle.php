<?php

namespace App\Models\Vehicles;

use App\Models\Documents\Document;
use App\Models\Globalization\Country;
use App\Models\Maps\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Vehicle extends Model
{
    protected $fillable = [
        'owner_id', 'country_id', 'brand_id', 'model_id', 'generation_id',
        'year', 'vin', 'registration_number', 'license_plate', 'color',
        'fuel_type_id', 'transmission_id', 'mileage', 'engine_number', 'status',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(VehicleBrand::class);
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function generation(): BelongsTo
    {
        return $this->belongsTo(VehicleGeneration::class);
    }

    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class);
    }

    public function transmission(): BelongsTo
    {
        return $this->belongsTo(Transmission::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(VehicleImage::class);
    }

    public function legacyDocuments(): HasMany
    {
        return $this->hasMany(VehicleDocument::class);
    }

    public function dmsDocuments(): MorphMany
    {
        return $this->morphMany(Document::class, 'owner');
    }

    public function history(): HasMany
    {
        return $this->hasMany(VehicleHistory::class);
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(VehicleUsageLog::class);
    }

    public function health(): MorphOne|HasMany|HasOne
    {
        return $this->hasOne(VehicleHealth::class);
    }

    public function location(): MorphOne
    {
        return $this->morphOne(Location::class, 'entity');
    }
}
