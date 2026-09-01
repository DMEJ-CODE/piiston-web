<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Vehicles\FuelType;
use App\Models\Vehicles\Transmission;
use App\Models\Vehicles\VehicleBrand;
use App\Models\Vehicles\VehicleModel;
use Illuminate\Http\JsonResponse;

class VehicleCatalogController extends Controller
{
    public function brands(): JsonResponse
    {
        return response()->json(VehicleBrand::where('status', true)->get());
    }

    public function models(int $brandId): JsonResponse
    {
        return response()->json(VehicleModel::where('brand_id', $brandId)->where('status', true)->get());
    }

    public function fuelTypes(): JsonResponse
    {
        return response()->json(FuelType::all());
    }

    public function transmissions(): JsonResponse
    {
        return response()->json(Transmission::all());
    }
}
