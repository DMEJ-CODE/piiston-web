<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OCRResult extends Model
{
    protected $table = 'dms_ocr_results';

    protected $fillable = ['document_id', 'provider', 'language_code', 'raw_text', 'structured_data', 'confidence_score'];

    protected $casts = [
        'structured_data' => 'array',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
