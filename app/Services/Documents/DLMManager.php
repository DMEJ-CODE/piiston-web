<?php

namespace App\Services\Documents;

use App\Models\Documents\Document;

class DLMManager
{
    /**
     * Handle the full lifecycle of a document
     */
    public function transition(Document $document, string $newStatus, array $context = [])
    {
        $oldStatus = $document->status;
        $document->update(['status' => $newStatus]);

        // Log to history
        $document->history()->create([
            'action' => 'STATUS_CHANGE',
            'performed_by' => auth()->id(),
            'created_at' => now(),
            'notes' => "Changed from $oldStatus to $newStatus",
        ]);

        if ($newStatus === 'APPROVED') {
            $this->handlePostApproval($document);
        }
    }

    protected function handlePostApproval(Document $document)
    {
        // Future: trigger digital seal or OCR indexing
    }

    public function applyRetentionPolicy(Document $document)
    {
        $policy = $document->type->retention;
        if (! $policy) {
            return;
        }

        $ageDays = $document->created_at->diffInDays(now());

        if ($ageDays > $policy->retention_period_days) {
            if ($policy->auto_delete) {
                $document->delete();
            } elseif ($policy->archive_after) {
                $document->update(['status' => 'ARCHIVED']);
            }
        }
    }
}
