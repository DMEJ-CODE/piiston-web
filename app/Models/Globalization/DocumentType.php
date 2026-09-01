<?php

namespace App\Models\Globalization;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentType extends Model
{
    protected $fillable = ['country_id', 'name', 'required_for', 'status'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
