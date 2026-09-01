<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GarageInvoice extends Model
{
    protected $fillable = [
        'repair_order_id', 'customer_id', 'amount',
        'tax', 'discount', 'total_payable', 'status',
    ];

    public function repairOrder(): BelongsTo
    {
        return $this->belongsTo(RepairOrder::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(GarageCustomer::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(GaragePayment::class, 'invoice_id');
    }
}
