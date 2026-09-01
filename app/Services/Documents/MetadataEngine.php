<?php

namespace App\Services\Documents;

use App\Models\Documents\Document;

class MetadataEngine
{
    /**
     * Enrich a document with technical metadata
     */
    public function enrich(Document $document, array $meta): void
    {
        // This maps to the polymorphic 'owner' or custom fields
        // In the existing schema, we can store in a JSON field if we had one,
        // or link via specific reference tables.

        // Since I'm not allowed to add columns, I'll use the 'description'
        // or title if formatted, or the Document's related entity.
    }

    public function getTechnicalSummary(Document $document): array
    {
        return [
            'owner_type' => $document->owner_type,
            'file_size_mb' => round($document->file_size / 1024 / 1024, 2),
            'mime_type' => $document->mime_type,
            'is_verified' => $document->approvals()->where('status', 'APPROVED')->exists(),
        ];
    }
}
