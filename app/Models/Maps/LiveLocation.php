<?php

namespace App\Models\Maps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveLocation extends Model
{
    public $timestamps = false;

    protected $fillable = ['tracking_session_id', 'latitude', 'longitude', 'speed', 'heading', 'accuracy', 'captured_at'];

    protected $casts = [
        'captured_at' => 'datetime',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(TrackingSession::class, 'tracking_session_id');
    }
}
