<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GarageSubscription extends Model
{
    protected $fillable = [
        'company_id', 'plan_id', 'status', 'billing_cycle',
        'starts_at', 'ends_at', 'trial_ends_at',
        'payment_method', 'transaction_reference',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(GarageCompany::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(GarageSubscriptionPlan::class);
    }
}
