<?php

namespace App\Models\Integrations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ApiRequest extends Model
{
    public $timestamps = false;

    protected $fillable = ['api_client_id', 'endpoint', 'method', 'ip_address', 'response_code', 'duration_ms', 'payload_summary', 'created_at'];

    protected $casts = [
        'payload_summary' => 'array',
        'created_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class, 'api_client_id');
    }

    public function response(): HasOne
    {
        return $this->hasOne(ApiResponse::class, 'request_id');
    }
}
