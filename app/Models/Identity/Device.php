<?php

namespace App\Models\Identity;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Device extends Model
{
    protected $fillable = ['user_id', 'device_token', 'platform', 'app_version', 'last_used'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
