<?php

namespace App\Models\Documents;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    protected $table = 'dms_documents';

    protected $fillable = [
        'owner_type', 'owner_id', 'category_id', 'type_id', 'folder_id',
        'title', 'description', 'file_name', 'file_path', 'mime_type',
        'file_size', 'checksum', 'visibility', 'status', 'created_by',
    ];

    public function owner()
    {
        return $this->morphTo();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class, 'category_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'type_id');
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(DocumentFolder::class, 'folder_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function mediaFile(): HasOne
    {
        return $this->hasOne(MediaFile::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(DocumentApproval::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(DocumentHistory::class);
    }

    public function ocrResult(): HasOne
    {
        return $this->hasOne(OCRResult::class);
    }

    public function expirations(): HasMany
    {
        return $this->hasMany(DocumentExpiration::class);
    }
}
