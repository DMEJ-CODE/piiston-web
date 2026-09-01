<?php

namespace App\Models\Mechanics;

use Illuminate\Database\Eloquent\Model;

class MechanicLocation extends Model
{
    protected $fillable = ['mechanic_id', 'latitude', 'longitude', 'radius_km', 'last_updated'];
}
