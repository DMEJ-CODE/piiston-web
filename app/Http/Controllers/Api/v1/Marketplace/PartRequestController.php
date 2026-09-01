<?php

namespace App\Http\Controllers\Api\v1\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\PartRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartRequestController extends Controller
{
    public function index(): JsonResponse
    {
        $requests = PartRequest::with(['vehicle.brand', 'vehicle.model'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($requests);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'description' => 'required|string',
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'quantity' => 'integer|min:1',
            'photo' => 'nullable|string', // Path or base64
        ]);

        $data['user_id'] = Auth::id();
        $data['status'] = 'OPEN';

        $partRequest = PartRequest::create($data);

        return response()->json($partRequest, 201);
    }

    public function show(int $id): JsonResponse
    {
        $partRequest = PartRequest::with(['vehicle.brand', 'vehicle.model', 'quotes.seller'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json($partRequest);
    }

    public function close(int $id): JsonResponse
    {
        $partRequest = PartRequest::where('user_id', Auth::id())->findOrFail($id);
        $partRequest->update(['status' => 'CLOSED']);

        return response()->json($partRequest);
    }
}
