<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dashboard extends Model
{
    protected $fillable = ['owner_type', 'owner_id', 'name', 'description', 'layout', 'is_default'];

    protected $casts = [
        'layout' => 'array',
        'is_default' => 'boolean',
    ];

    public function widgets(): HasMany
    {
        return $this->hasMany(DashboardWidget::class);
    }

    public function owner()
    {
        return $this->morphTo();
    }
}
