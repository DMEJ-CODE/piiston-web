<?php

namespace App\Models\Garages;

use App\Models\User;
use App\Models\Vehicles\Vehicle;
use App\Models\Workflows\QualityCheck;
use App\Models\Workflows\RepairApproval;
use App\Models\Workflows\RepairProgress;
use App\Models\Workflows\RepairWarranty;
use App\Models\Workflows\VehicleDelivery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RepairOrder extends Model
{
    // Lifecycle Statuses
    const STATUS_REQUESTED = 'REQUESTED';

    const STATUS_DIAGNOSIS = 'DIAGNOSIS';

    const STATUS_QUOTE_CREATED = 'QUOTE_CREATED';

    const STATUS_WAITING_APPROVAL = 'WAITING_APPROVAL';

    const STATUS_APPROVED = 'APPROVED';

    const STATUS_SCHEDULED = 'SCHEDULED';

    const STATUS_IN_PROGRESS = 'IN_PROGRESS';

    const STATUS_WAITING_PARTS = 'WAITING_PARTS';

    const STATUS_COMPLETED = 'COMPLETED';

    const STATUS_QUALITY_CHECK = 'QUALITY_CHECK';

    const STATUS_DELIVERED = 'DELIVERED';

    const STATUS_CLOSED = 'CLOSED';

    protected $fillable = [
        'check_in_id', 'branch_id', 'vehicle_id', 'customer_id', 'appointment_id',
        'workshop_bay_id', 'assigned_mechanic_id', 'problem_description', 'internal_notes', 'priority',
        'status', 'status_history', 'estimated_cost', 'final_cost', 'opened_at', 'closed_at',
        'quality_check_at', 'quality_checked_by',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'quality_check_at' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
        'status_history' => 'array',
    ];

    public function checkIn(): BelongsTo
    {
        return $this->belongsTo(VehicleCheckIn::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function garageCustomer(): BelongsTo
    {
        return $this->belongsTo(GarageCustomer::class, 'customer_id');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_mechanic_id');
    }

    public function bay(): BelongsTo
    {
        return $this->belongsTo(WorkshopBay::class);
    }

    public function diagnosis(): HasOne
    {
        return $this->hasOne(VehicleDiagnosis::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(RepairTask::class);
    }

    public function parts(): HasMany
    {
        return $this->hasMany(RepairPartUsage::class);
    }

    public function estimate(): HasOne
    {
        return $this->hasOne(RepairEstimate::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(GarageInvoice::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(RepairProgress::class)->orderBy('created_at', 'desc');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(RepairApproval::class);
    }

    public function qualityCheck(): HasOne
    {
        return $this->hasOne(QualityCheck::class);
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(VehicleDelivery::class);
    }

    public function warranty(): HasOne
    {
        return $this->hasOne(RepairWarranty::class);
    }

    public function qualityCheckedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'quality_checked_by');
    }

    public function media(): HasMany
    {
        return $this->hasMany(GarageMedia::class, 'mediable_id')->where('mediable_type', self::class);
    }
}
