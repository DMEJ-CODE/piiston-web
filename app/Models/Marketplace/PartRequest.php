<?php

namespace App\Models\Marketplace;

use App\Models\User;
use App\Models\Vehicles\Vehicle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartRequest extends Model
{
    protected $fillable = ['user_id', 'vehicle_id', 'description', 'photo', 'quantity', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
