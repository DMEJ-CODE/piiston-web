<?php

namespace App\Models\Mechanics;

use Illuminate\Database\Eloquent\Model;

class MechanicTool extends Model
{
    protected $fillable = ['mechanic_id', 'name', 'category', 'condition'];
}
