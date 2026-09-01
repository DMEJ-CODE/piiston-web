<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformReport extends Model
{
    protected $fillable = ['report_type', 'title', 'description', 'status', 'generated_by', 'file_path', 'generated_at'];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Administrator::class, 'generated_by');
    }

    public function getReporterAttribute(): ?string
    {
        return $this->creator?->user?->name;
    }
}
