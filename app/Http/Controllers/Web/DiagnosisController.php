<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\RepairOrder;
use App\Models\Garages\VehicleDiagnosis;
use App\Services\AI\DiagnosisService;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = VehicleDiagnosis::whereHas('repairOrder', function ($q) use ($branch) {
            $q->where('branch_id', $branch->id);
        })->with(['repairOrder.vehicle', 'repairOrder.garageCustomer.user', 'mechanic']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('detected_problem', 'like', "%{$search}%")
                    ->orWhereHas('repairOrder.vehicle', function ($vq) use ($search) {
                        $vq->where('license_plate', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $diagnoses = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('garage.diagnoses.index', [
            'diagnoses' => $diagnoses,
            'branch' => $branch,
        ]);
    }

    public function create(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $repairs = $branch->repairOrders()
            ->with(['vehicle', 'garageCustomer.user'])
            ->whereNotIn('status', ['COMPLETED', 'DELIVERED'])
            ->get();

        return view('garage.diagnoses.create', [
            'branch' => $branch,
            'repairs' => $repairs,
        ]);
    }

    public function store(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $request->validate([
            'repair_order_id' => ['required', 'exists:repair_orders,id'],
            'symptoms' => ['nullable', 'string', 'max:2000'],
            'detected_problem' => ['required', 'string', 'max:2000'],
            'root_cause' => ['nullable', 'string', 'max:2000'],
            'solution' => ['nullable', 'string', 'max:2000'],
            'recommendation' => ['nullable', 'string', 'max:2000'],
            'severity' => ['required', 'string', 'in:low,medium,high,critical'],
        ]);

        $repair = RepairOrder::where('branch_id', $branch->id)
            ->findOrFail($request->repair_order_id);

        $diagnosis = VehicleDiagnosis::updateOrCreate(
            ['repair_order_id' => $repair->id],
            array_merge($request->validated(), [
                'mechanic_id' => $request->user()->id,
            ])
        );

        $repair->update(['status' => RepairOrder::STATUS_DIAGNOSIS]);

        return redirect()->route('garage.repairs.show', $repair->id)->with('success', 'Diagnostic enregistré avec succès.');
    }

    public function update(Request $request, $diagnosisId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $diagnosis = VehicleDiagnosis::findOrFail($diagnosisId);
        $this->authorize('update', $diagnosis->repairOrder);

        $request->validate([
            'symptoms' => ['nullable', 'string', 'max:2000'],
            'detected_problem' => ['required', 'string', 'max:2000'],
            'root_cause' => ['nullable', 'string', 'max:2000'],
            'solution' => ['nullable', 'string', 'max:2000'],
            'recommendation' => ['nullable', 'string', 'max:2000'],
            'severity' => ['required', 'string', 'in:low,medium,high,critical'],
        ]);

        $diagnosis->update($request->validated());

        return redirect()->route('garage.repairs.show', $diagnosis->repair_order_id)->with('success', 'Diagnostic mis à jour.');
    }

    public function runAI(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)->findOrFail($repairId);

        $diagnosis = VehicleDiagnosis::firstOrCreate(
            ['repair_order_id' => $repair->id],
            [
                'mechanic_id' => $request->user()->id,
                'symptoms' => $repair->problem_description,
                'detected_problem' => 'Analyse IA en cours...',
                'severity' => 'medium',
            ]
        );

        app(DiagnosisService::class)->suggestWorkshopDiagnosis($diagnosis);

        return back()->with('success', 'Assistant IA : Analyse terminée.');
    }

    public function destroy(Request $request, $diagnosisId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $diagnosis = VehicleDiagnosis::whereHas('repairOrder', function ($q) use ($branch) {
            $q->where('branch_id', $branch->id);
        })->findOrFail($diagnosisId);

        $diagnosis->delete();

        return redirect()->route('garage.diagnoses.index')->with('success', 'Diagnostic supprimé avec succès.');
    }
}
