<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalMapping extends Model
{
    public $timestamps = false;

    protected $fillable = ['integration_id', 'local_field', 'external_field'];

    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }
}
