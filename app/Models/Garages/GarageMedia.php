<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GarageMedia extends Model
{
    protected $fillable = [
        'branch_id',
        'mediable_id',
        'mediable_type',
        'type',
        'path',
        'original_name',
        'size',
        'mime_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function branch()
    {
        return $this->belongsTo(GarageBranch::class, 'branch_id');
    }
}
