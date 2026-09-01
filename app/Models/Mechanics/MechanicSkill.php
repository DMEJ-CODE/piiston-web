<?php

namespace App\Models\Mechanics;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MechanicSkill extends Model
{
    protected $fillable = ['name', 'category', 'description'];

    public function mechanics(): BelongsToMany
    {
        return $this->belongsToMany(MechanicProfile::class, 'mechanic_skill_assignments', 'skill_id', 'mechanic_id');
    }
}
