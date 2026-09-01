<?php

namespace App\Models\Maps;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NearbySearch extends Model
{
    protected $fillable = ['user_id', 'search_type', 'radius_meters', 'latitude', 'longitude'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
