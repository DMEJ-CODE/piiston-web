<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Audience extends Model
{
    protected $fillable = ['name', 'description', 'estimated_size'];

    public function rules(): HasMany
    {
        return $this->hasMany(AudienceRule::class);
    }
}
