<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    protected $fillable = ['module_name', 'table_name', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
