<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    protected $table = 'dms_templates';

    protected $fillable = ['name', 'category', 'template_file', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
