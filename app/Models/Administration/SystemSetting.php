<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemSetting extends Model
{
    protected $fillable = ['setting_key', 'setting_value', 'description', 'updated_by'];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Administrator::class, 'updated_by');
    }
}
