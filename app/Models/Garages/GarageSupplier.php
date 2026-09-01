<?php

namespace App\Models\Garages;

use App\Models\Globalization\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GarageSupplier extends Model
{
    protected $fillable = ['company_id', 'name', 'phone', 'email', 'address_id', 'status'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(GarageCompany::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
