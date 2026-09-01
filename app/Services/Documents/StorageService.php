<?php

namespace App\Services\Documents;

use Illuminate\Support\Facades\Storage;

class StorageService
{
    /**
     * Generate a temporary signed URL for viewing
     */
    public function getTemporaryUrl(string $path, int $minutes = 60)
    {
        return Storage::temporaryUrl($path, now()->addMinutes($minutes));
    }

    /**
     * Delete a file from storage
     */
    public function delete(string $path)
    {
        return Storage::delete($path);
    }
}
