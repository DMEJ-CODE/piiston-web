<?php

namespace App\Http\Controllers\Api\v1\Marketplace\Seller;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index(): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $suppliers = Supplier::where('seller_id', $seller->id)->get();

        return response()->json($suppliers);
    }

    public function store(Request $request): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $data = $request->validate([
            'name' => 'required|string',
            'contact_person' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'category' => 'nullable|string',
            'tax_id' => 'nullable|string',
        ]);

        $data['seller_id'] = $seller->id;
        $supplier = Supplier::create($data);

        return response()->json($supplier, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $supplier = Supplier::where('seller_id', $seller->id)->findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'contact_person' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'category' => 'nullable|string',
            'tax_id' => 'nullable|string',
            'status' => 'sometimes|boolean',
        ]);

        $supplier->update($data);

        return response()->json($supplier);
    }

    public function destroy(int $id): JsonResponse
    {
        $seller = Auth::user()->sellerProfile;
        $supplier = Supplier::where('seller_id', $seller->id)->findOrFail($id);
        $supplier->delete();

        return response()->json(['message' => 'Supplier deleted']);
    }
}
