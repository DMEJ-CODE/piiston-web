<?php

namespace App\Models\Maps;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TrackingSession extends Model
{
    protected $fillable = ['entity_type', 'entity_id', 'started_by', 'status', 'started_at', 'ended_at'];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'started_by');
    }

    public function liveLocation(): HasOne
    {
        return $this->hasOne(LiveLocation::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(LocationHistory::class);
    }

    public function entity()
    {
        return $this->morphTo();
    }
}
