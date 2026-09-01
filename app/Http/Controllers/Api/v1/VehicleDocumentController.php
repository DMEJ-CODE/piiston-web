<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Vehicles\Vehicle;
use App\Models\Vehicles\VehicleDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VehicleDocumentController extends Controller
{
    public function index(int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        if ($vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($vehicle->documents);
    }

    public function store(Request $request, int $vehicleId): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($vehicleId);

        if ($vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'type' => 'required|string|max:100', // e.g., CARTE_GRISE, INSURANCE
            'document_number' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store("vehicles/$vehicleId/documents", 'public');
            $validated['file_url'] = Storage::url($path);
        }

        unset($validated['file']);
        $document = $vehicle->documents()->create($validated);

        return response()->json([
            'message' => 'Document uploaded successfully',
            'document' => $document,
        ], 201);
    }

    public function destroy(int $vehicleId, int $id): JsonResponse
    {
        $document = VehicleDocument::where('vehicle_id', $vehicleId)->findOrFail($id);

        if ($document->vehicle->owner_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete file from storage
        if ($document->file_url) {
            $path = str_replace('/storage/', '', $document->file_url);
            Storage::disk('public')->delete($path);
        }

        $document->delete();

        return response()->json(['message' => 'Document deleted']);
    }
}
