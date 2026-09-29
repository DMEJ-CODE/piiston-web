<?php

namespace App\Models\Garages;

use App\Concerns\NormalizesBooleanStatus;
use App\Models\Globalization\Address;
use App\Models\Maps\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class GarageBranch extends Model
{
    use NormalizesBooleanStatus;

    protected $fillable = [
        'company_id', 'address_id', 'manager_id', 'name', 'logo_path', 'cover_path',
        'phone', 'email', 'opening_date', 'status', 'business_hours', 'social_links',
        'latitude', 'longitude',
    ];

    protected $casts = [
        'business_hours' => 'array',
        'social_links' => 'array',
        'status' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(GarageCompany::class, 'company_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(GarageDepartment::class, 'branch_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(GarageEmployee::class, 'branch_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(GarageService::class, 'branch_id');
    }

    public function bays(): HasMany
    {
        return $this->hasMany(WorkshopBay::class, 'branch_id');
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(GarageInventory::class, 'branch_id');
    }

    public function repairOrders(): HasMany
    {
        return $this->hasMany(RepairOrder::class, 'branch_id');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(GarageCustomer::class, 'branch_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(GarageAppointment::class, 'branch_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(GaragePurchaseOrder::class, 'branch_id');
    }

    public function location(): MorphOne
    {
        return $this->morphOne(Location::class, 'entity');
    }
}
