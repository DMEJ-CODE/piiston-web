<?php

namespace App\Http\Controllers\Api\Documents;

use App\Http\Controllers\Controller;
use App\Models\Documents\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionController extends Controller
{
    public function approve(Request $request, Document $document)
    {
        $document->approvals()->create([
            'approved_by' => Auth::id(),
            'status' => $request->status ?? 'APPROVED',
            'notes' => $request->notes,
            'approved_at' => now(),
        ]);

        return response()->json(['message' => 'Approval recorded']);
    }

    public function share(Request $request, Document $document)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $document->permissions()->updateOrCreate(
            ['user_id' => $request->user_id],
            ['permission' => 'VIEW']
        );

        return response()->json(['message' => 'Document shared successfully']);
    }
}
