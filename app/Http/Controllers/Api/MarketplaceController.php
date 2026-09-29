<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\PartBrand;
use App\Models\Marketplace\PartCategory;
use App\Models\Marketplace\ProductListing;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductListing::with(['part.brand', 'part.category', 'seller', 'currency'])
            ->whereIn('status', ['active', 1, 'ACTIVE', 'TRUE', true]);

        // Filter by vehicle compatibility
        if ($request->has('vehicle_model_id')) {
            $query->whereHas('part.compatibilities', function ($q) use ($request) {
                $q->where('vehicle_model_id', $request->vehicle_model_id);
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->whereHas('part', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        return response()->json($query->paginate(20));
    }

    public function categories()
    {
        return response()->json(PartCategory::where('status', true)->get());
    }

    public function brands()
    {
        return response()->json(PartBrand::where('status', true)->get());
    }

    public function show($id)
    {
        return response()->json(
            ProductListing::with(['part.brand', 'part.category', 'part.compatibilities.vehicleModel', 'seller', 'currency'])
                ->findOrFail($id)
        );
    }
}
