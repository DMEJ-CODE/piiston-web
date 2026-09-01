<?php

namespace App\Models\BI;

use Illuminate\Database\Eloquent\Model;

class BusinessInsight extends Model
{
    public $timestamps = false;

    protected $fillable = ['owner_type', 'owner_id', 'title', 'description', 'severity', 'recommendation', 'generated_at'];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->morphTo();
    }
}
