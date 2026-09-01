<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Integration extends Model
{
    protected $fillable = ['provider_id', 'name', 'configuration', 'status'];

    protected $casts = [
        'configuration' => 'array',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(IntegrationProvider::class, 'provider_id');
    }

    public function credentials(): HasMany
    {
        return $this->hasMany(IntegrationCredential::class);
    }
}
