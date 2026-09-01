<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Model;

class ApiVersion extends Model
{
    protected $fillable = ['version_name', 'release_date', 'status'];

    protected $casts = [
        'release_date' => 'date',
    ];
}
