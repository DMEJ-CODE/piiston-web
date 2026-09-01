<?php

namespace App\Models\Integrations;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DeveloperApplication extends Model
{
    protected $fillable = ['developer_id', 'app_name', 'description', 'status'];

    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(DeveloperSubscription::class, 'application_id');
    }
}
