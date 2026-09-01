<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Globalization\Address;
use App\Models\Identity\UserAddress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index(): JsonResponse
    {
        $addresses = UserAddress::where('user_id', Auth::id())
            ->with(['address.city', 'address.region', 'address.country'])
            ->get();

        return response()->json($addresses);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'region_id' => 'required|exists:regions,id',
            'city_id' => 'required|exists:cities,id',
            'street' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'type' => 'required|string|in:HOME,WORK,OTHER',
            'is_default' => 'boolean',
        ]);

        $address = Address::create([
            'country_id' => $validated['country_id'],
            'region_id' => $validated['region_id'],
            'city_id' => $validated['city_id'],
            'street' => $validated['street'],
            'postal_code' => $validated['postal_code'],
        ]);

        if ($validated['is_default'] ?? false) {
            UserAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $userAddress = UserAddress::create([
            'user_id' => Auth::id(),
            'address_id' => $address->id,
            'type' => $validated['type'],
            'is_default' => $validated['is_default'] ?? false,
        ]);

        return response()->json($userAddress->load('address'), 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $userAddress = UserAddress::where('user_id', Auth::id())->findOrFail($id);
        $userAddress->delete();

        // We might want to keep the Address record itself if shared, but usually fine to leave it.
        return response()->json(['message' => 'Address removed from profile']);
    }
}
