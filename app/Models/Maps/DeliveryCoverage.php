<?php

namespace App\Models\Maps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryCoverage extends Model
{
    protected $fillable = ['delivery_zone_id', 'district', 'postal_code'];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(DeliveryZone::class, 'delivery_zone_id');
    }
}
