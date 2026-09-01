<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KPI extends Model
{
    protected $table = 'kpis';

    protected $fillable = ['name', 'code', 'category', 'formula', 'unit', 'target_value', 'status'];

    public function metrics(): HasMany
    {
        return $this->hasMany(Metric::class, 'kpi_id');
    }
}
