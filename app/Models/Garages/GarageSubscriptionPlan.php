<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;

class GarageSubscriptionPlan extends Model
{
    protected $table = 'garage_subscription_plans';

    protected $fillable = [
        'name', 'slug', 'description', 'monthly_price', 'yearly_price',
        'max_branches', 'max_employees', 'max_vehicles_per_month', 'max_storage_gb',
        'features', 'is_active',
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'yearly_price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
    ];
}
