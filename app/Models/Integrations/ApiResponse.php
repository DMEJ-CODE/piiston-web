<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiResponse extends Model
{
    public $timestamps = false;

    protected $fillable = ['request_id', 'status_code', 'payload_size', 'created_at'];

    public function request(): BelongsTo
    {
        return $this->belongsTo(ApiRequest::class, 'request_id');
    }
}
