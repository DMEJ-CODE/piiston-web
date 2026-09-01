<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessVerification extends Model
{
    protected $fillable = ['business_type', 'business_id', 'verified_by', 'verification_status', 'verification_notes', 'verified_at'];

    public function administrator(): BelongsTo
    {
        return $this->belongsTo(Administrator::class, 'verified_by');
    }
}
