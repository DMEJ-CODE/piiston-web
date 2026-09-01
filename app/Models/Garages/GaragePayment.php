<?php

namespace App\Models\Garages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GaragePayment extends Model
{
    protected $fillable = [
        'invoice_id', 'payment_method', 'amount',
        'transaction_reference', 'status', 'payment_date',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(GarageInvoice::class);
    }
}
