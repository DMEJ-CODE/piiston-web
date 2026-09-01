<?php

namespace App\Http\Controllers\Api\AI;

use App\Http\Controllers\Controller;
use App\Models\Vehicles\Vehicle;
use App\Services\AI\DiagnosisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiagnosisController extends Controller
{
    protected $diagnosisService;

    public function __construct(DiagnosisService $diagnosisService)
    {
        $this->diagnosisService = $diagnosisService;
    }

    public function index()
    {
        return response()->json(
            Auth::user()->aiDiagnoses()->with('vehicle')->orderBy('created_at', 'desc')->get()
        );
    }

    public function diagnose(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'symptoms' => 'required|string',
            'dtc_code' => 'nullable|string',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

        $diagnosis = $this->diagnosisService->diagnoseVehicle(
            $vehicle,
            $validated['symptoms'],
            $validated['dtc_code']
        );

        return response()->json($diagnosis, 201);
    }
}
