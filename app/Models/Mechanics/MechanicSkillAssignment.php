<?php

namespace App\Models\Mechanics;

use Illuminate\Database\Eloquent\Model;

class MechanicSkillAssignment extends Model
{
    protected $table = 'mechanic_skill_assignments';

    protected $fillable = ['mechanic_id', 'skill_id', 'experience_level', 'certification', 'years_practiced'];
}
