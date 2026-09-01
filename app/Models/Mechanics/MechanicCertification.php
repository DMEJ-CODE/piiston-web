<?php

namespace App\Models\Mechanics;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MechanicCertification extends Model
{
    protected $fillable = [
        'mechanic_id', 'name', 'organization', 'certificate_number',
        'issue_date', 'expiry_date', 'document_file', 'verification_status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(MechanicProfile::class, 'mechanic_id');
    }
}
