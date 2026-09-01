<?php

namespace App\Models\Maps;

use Illuminate\Database\Eloquent\Model;

class RoutePoint extends Model
{
    public $timestamps = false;

    protected $fillable = ['route_id', 'latitude', 'longitude', 'sequence'];
}
