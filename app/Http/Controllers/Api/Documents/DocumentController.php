<?php

namespace App\Http\Controllers\Api\Documents;

use App\Http\Controllers\Controller;
use App\Models\Documents\Document;
use App\Models\Documents\DocumentCategory;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        return response()->json(
            Auth::user()->ownedDocuments()->with(['category', 'type'])->get()
        );
    }

    public function categories()
    {
        return response()->json(DocumentCategory::with('types')->get());
    }

    public function show(Document $document)
    {
        // Simple security check
        if ($document->created_by !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($document->load(['versions', 'approvals', 'ocrResult']));
    }

    public function download(Document $document)
    {
        if ($document->created_by !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return \Storage::download($document->file_path, $document->file_name);
    }
}
