<?php

namespace App\Models\Administration;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FraudCase extends Model
{
    protected $fillable = ['user_id', 'case_type', 'description', 'evidence', 'risk_level', 'status'];

    public function reported_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFraudTypeAttribute(): string
    {
        return $this->case_type;
    }
}
