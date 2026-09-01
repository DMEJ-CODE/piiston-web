<?php

namespace App\Models\Maps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeofenceEvent extends Model
{
    public $timestamps = false;

    protected $fillable = ['geofence_id', 'entity_type', 'entity_id', 'event_type', 'occurred_at'];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function geofence(): BelongsTo
    {
        return $this->belongsTo(Geofence::class);
    }

    public function entity()
    {
        return $this->morphTo();
    }
}
