<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaFile extends Model
{
    protected $table = 'dms_media_files';

    protected $fillable = ['document_id', 'media_type', 'thumbnail', 'duration_seconds', 'resolution'];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
