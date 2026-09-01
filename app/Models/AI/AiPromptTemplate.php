<?php

namespace App\Models\AI;

use Illuminate\Database\Eloquent\Model;

class AiPromptTemplate extends Model
{
    protected $fillable = ['name', 'purpose', 'system_prompt', 'variables', 'version', 'status'];

    protected $casts = [
        'variables' => 'array',
        'status' => 'boolean',
    ];
}
