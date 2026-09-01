<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiClient extends Model
{
    protected $fillable = ['name', 'type', 'owner_type', 'owner_id', 'status'];

    public function keys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(ApiRequest::class);
    }
}
