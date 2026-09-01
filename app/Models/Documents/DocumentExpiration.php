<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentExpiration extends Model
{
    protected $table = 'dms_expirations';

    protected $fillable = ['document_id', 'expiration_date', 'notification_sent', 'status'];

    protected $casts = [
        'expiration_date' => 'date',
        'notification_sent' => 'boolean',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
