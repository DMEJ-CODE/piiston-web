<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromotionType extends Model
{
    protected $fillable = ['name', 'description', 'rules'];

    protected $casts = [
        'rules' => 'array',
    ];

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }
}
