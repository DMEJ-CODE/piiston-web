<?php

namespace App\Models\Mechanics;

use Illuminate\Database\Eloquent\Model;

class MechanicExperience extends Model
{
    protected $fillable = ['mechanic_id', 'company_name', 'position', 'description', 'start_date', 'end_date'];
}
