<?php

namespace App\Models\BI;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $fillable = ['owner_type', 'owner_id', 'report_type', 'title', 'description', 'generated_by', 'generated_at'];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function executions(): HasMany
    {
        return $this->hasMany(ReportExecution::class);
    }

    public function shares(): HasMany
    {
        return $this->hasMany(ReportShare::class);
    }
}
