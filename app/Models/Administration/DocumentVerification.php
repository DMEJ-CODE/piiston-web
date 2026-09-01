<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVerification extends Model
{
    protected $fillable = ['document_id', 'reviewed_by', 'status', 'reason', 'reviewed_at'];

    public function administrator(): BelongsTo
    {
        return $this->belongsTo(Administrator::class, 'reviewed_by');
    }
}
