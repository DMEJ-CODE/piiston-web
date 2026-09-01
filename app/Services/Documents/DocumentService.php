<?php

namespace App\Services\Documents;

use App\Models\Documents\Document;
use App\Models\Documents\DocumentVersion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class DocumentService
{
    /**
     * Upload and index a new document
     */
    public function upload(UploadedFile $file, Model $owner, array $data)
    {
        $path = $file->store('documents/'.$owner->id);

        $document = Document::create([
            'owner_type' => get_class($owner),
            'owner_id' => $owner->id,
            'category_id' => $data['category_id'],
            'type_id' => $data['type_id'],
            'title' => $data['title'] ?? $file->getClientOriginalName(),
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'created_by' => auth()->id(),
            'status' => 'active',
        ]);

        // Create initial version
        DocumentVersion::create([
            'document_id' => $document->id,
            'version' => 1,
            'file_path' => $path,
            'created_by' => auth()->id(),
        ]);

        return $document;
    }

    /**
     * Create a new version of an existing document
     */
    public function updateVersion(Document $document, UploadedFile $file)
    {
        $path = $file->store('documents/'.$document->owner_id);
        $newVersionNumber = $document->versions()->max('version') + 1;

        $version = DocumentVersion::create([
            'document_id' => $document->id,
            'version' => $newVersionNumber,
            'file_path' => $path,
            'created_by' => auth()->id(),
        ]);

        $document->update([
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return $version;
    }
}
