<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function complete(Request $request): JsonResponse
    {
        $user = Auth::user();
        $seller = $user->sellerProfile;

        if (! $seller) {
            return response()->json(['message' => 'Seller profile not found'], 404);
        }

        $data = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string',
            'registration_number' => 'required|string',
            'tax_number' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $seller->update(array_merge($data, ['is_onboarded' => true]));

        return response()->json([
            'message' => 'Onboarding completed successfully',
            'seller' => $seller->fresh(),
        ]);
    }
}
