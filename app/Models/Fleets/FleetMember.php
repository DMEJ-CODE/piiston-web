<?php

namespace App\Models\Fleets;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FleetMember extends Model
{
    protected $fillable = ['fleet_id', 'user_id', 'role', 'permissions', 'status'];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
