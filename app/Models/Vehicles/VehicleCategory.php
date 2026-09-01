<?php

namespace App\Models\Vehicles;

use Illuminate\Database\Eloquent\Model;

class VehicleCategory extends Model
{
    protected $fillable = ['name', 'description', 'status'];
}
