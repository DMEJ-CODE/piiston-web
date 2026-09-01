<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IntegrationProvider extends Model
{
    protected $fillable = ['name', 'category', 'documentation_url', 'status'];

    public function integrations(): HasMany
    {
        return $this->hasMany(Integration::class, 'provider_id');
    }
}
