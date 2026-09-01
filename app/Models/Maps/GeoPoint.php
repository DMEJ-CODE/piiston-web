<?php

namespace App\Models\Maps;

use Illuminate\Database\Eloquent\Model;

class GeoPoint extends Model
{
    protected $fillable = ['name', 'latitude', 'longitude', 'type'];
}
