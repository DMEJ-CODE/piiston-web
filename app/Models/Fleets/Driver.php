<?php

namespace App\Models\Fleets;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{
    protected $fillable = [
        'user_id', 'company_id', 'license_number', 'license_category',
        'license_expiry_date', 'experience_years', 'status',
    ];

    protected $casts = [
        'license_expiry_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
