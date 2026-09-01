<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkshopBay extends Model
{
    protected $fillable = ['branch_id', 'name', 'type', 'capacity', 'status'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GarageBranch::class);
    }
}
