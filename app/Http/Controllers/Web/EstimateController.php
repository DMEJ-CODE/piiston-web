<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\RepairEstimate;
use App\Models\Garages\RepairOrder;
use Illuminate\Http\Request;

class EstimateController extends Controller
{
    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = RepairEstimate::where('branch_id', $branch->id)
            ->with(['repairOrder.vehicle', 'repairOrder.garageCustomer.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('repairOrder.vehicle', function ($q) use ($search) {
                $q->where('license_plate', 'like', "%{$search}%");
            })->orWhereHas('repairOrder.garageCustomer.user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $estimates = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('garage.estimates.index', [
            'estimates' => $estimates,
            'branch' => $branch,
        ]);
    }

    public function create(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $repairs = $branch->repairOrders()
            ->with(['vehicle', 'garageCustomer.user', 'diagnosis'])
            ->where('status', 'DIAGNOSIS') // Only allow estimates for diagnosed vehicles
            ->get();

        return view('garage.estimates.create', [
            'branch' => $branch,
            'repairs' => $repairs,
        ]);
    }

    public function store(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $request->validate([
            'repair_order_id' => ['required', 'exists:repair_orders,id'],
            'labor_cost' => ['required', 'numeric', 'min:0'],
            'parts_cost' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'valid_until' => ['required', 'date', 'after:today'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $repair = RepairOrder::where('branch_id', $branch->id)
            ->findOrFail($request->repair_order_id);

        RepairEstimate::create(array_merge($request->validated(), [
            'branch_id' => $branch->id,
            'status' => 'PENDING',
        ]));

        $repair->update(['status' => 'ESTIMATE_PENDING', 'estimated_cost' => $request->total_amount]);

        return redirect()->route('garage.estimates.index')->with('success', 'Devis créé avec succès.');
    }

    public function edit(Request $request, $estimateId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $estimate = RepairEstimate::where('branch_id', $branch->id)->findOrFail($estimateId);
        $repairs = $branch->repairOrders()
            ->with(['vehicle', 'garageCustomer.user', 'diagnoses'])
            ->get();

        return view('garage.estimates.edit', [
            'branch' => $branch,
            'estimate' => $estimate,
            'repairs' => $repairs,
        ]);
    }

    public function update(Request $request, $estimateId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $estimate = RepairEstimate::where('branch_id', $branch->id)->findOrFail($estimateId);

        $request->validate([
            'repair_order_id' => ['required', 'exists:repair_orders,id'],
            'labor_cost' => ['required', 'numeric', 'min:0'],
            'parts_cost' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'valid_until' => ['required', 'date', 'after:today'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'status' => ['nullable', 'string', 'in:PENDING,APPROVED,REJECTED,EXPIRED'],
        ]);

        $estimate->update($request->validated());

        return redirect()->route('garage.estimates.index')->with('success', 'Devis mis à jour avec succès.');
    }

    public function destroy(Request $request, $estimateId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $estimate = RepairEstimate::where('branch_id', $branch->id)->findOrFail($estimateId);
        $estimate->delete();

        return redirect()->route('garage.estimates.index')->with('success', 'Devis supprimé avec succès.');
    }

    public function approve(Request $request, $estimateId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $estimate = RepairEstimate::where('branch_id', $branch->id)->findOrFail($estimateId);
        $estimate->update(['status' => 'APPROVED']);

        $estimate->repairOrder->update(['status' => 'ESTIMATE_APPROVED']);

        return back()->with('success', 'Devis approuvé avec succès.');
    }

    public function reject(Request $request, $estimateId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $estimate = RepairEstimate::where('branch_id', $branch->id)->findOrFail($estimateId);
        $estimate->update(['status' => 'REJECTED']);

        return back()->with('success', 'Devis rejeté.');
    }
}
