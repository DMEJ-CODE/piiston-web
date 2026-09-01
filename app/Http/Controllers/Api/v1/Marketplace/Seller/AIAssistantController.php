<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AIAssistantController extends Controller
{
    public function identifyPart(Request $request): JsonResponse
    {
        $request->validate(['photo' => 'required']);

        // Mock AI Logic
        return response()->json([
            'suggested_name' => 'Brake Pad Set',
            'confidence' => 0.94,
            'suggested_category_id' => 5,
            'possible_brands' => ['Brembo', 'Bosch', 'TRW'],
        ]);
    }

    public function generateDescription(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'brand' => 'nullable|string',
            'condition' => 'nullable|string',
        ]);

        // Mock AI Logic
        $name = $data['name'];
        $brand = $data['brand'] ?? 'Generic';
        $condition = $data['condition'] ?? 'NEW';

        $description = "High-quality {$name} by {$brand}. This component is in {$condition} condition and designed for maximum durability and performance. Optimized for standard replacement cycles.";

        return response()->json(['description' => $description]);
    }
}
