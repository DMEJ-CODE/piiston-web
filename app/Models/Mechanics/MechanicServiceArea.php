<?php

namespace App\Models\Mechanics;

use Illuminate\Database\Eloquent\Model;

class MechanicServiceArea extends Model
{
    protected $fillable = ['mechanic_id', 'city_id', 'maximum_distance'];
}
