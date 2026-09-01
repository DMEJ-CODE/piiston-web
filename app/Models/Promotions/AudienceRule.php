<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudienceRule extends Model
{
    protected $fillable = ['audience_id', 'field', 'operator', 'value'];

    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class);
    }
}
