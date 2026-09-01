<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    protected $fillable = ['feature_name', 'description', 'enabled', 'rollout_percentage', 'target_country', 'target_role'];

    protected $casts = [
        'enabled' => 'boolean',
        'rollout_percentage' => 'integer',
    ];

    public function getNameAttribute(): string
    {
        return $this->feature_name;
    }
}
