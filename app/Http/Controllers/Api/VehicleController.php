<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicles\Vehicle;
use App\Models\Vehicles\VehicleBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        return response()->json(
            Auth::user()->vehicles()->with(['brand', 'model', 'generation', 'fuelType', 'health'])->get()
        );
    }

    public function brands()
    {
        return response()->json(VehicleBrand::where('status', true)->orderBy('name')->get());
    }

    public function models(VehicleBrand $brand)
    {
        return response()->json($brand->models()->where('status', true)->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id' => 'required|exists:vehicle_brands,id',
            'model_id' => 'required|exists:vehicle_models,id',
            'year' => 'nullable|integer',
            'license_plate' => 'nullable|string',
            'vin' => 'nullable|string|unique:vehicles,vin',
            'country_id' => 'required|exists:countries,id',
        ]);

        $vehicle = Auth::user()->vehicles()->create($validated);

        // Initialize health score
        $vehicle->health()->create(['health_score' => 100, 'risk_level' => 'low']);

        return response()->json($vehicle->load(['brand', 'model', 'health']), 201);
    }

    public function show(Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);

        return response()->json($vehicle->load([
            'brand', 'model', 'generation', 'fuelType',
            'transmission', 'health', 'maintenances', 'alerts',
        ]));
    }
}
