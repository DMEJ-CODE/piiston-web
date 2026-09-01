<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\WarrantyClaim;
use App\Services\Garages\AuditService;
use Illuminate\Http\Request;

class GarageWarrantyClaimController extends Controller
{
    public function __construct(protected AuditService $auditService) {}

    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = WarrantyClaim::where('branch_id', $branch->id)
            ->with(['repairOrder.vehicle', 'customer.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('repairOrder.vehicle', function ($q) use ($search) {
                $q->where('license_plate', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $claims = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('garage.warranty-claims.index', [
            'warrantyClaims' => $claims,
            'branch' => $branch,
        ]);
    }

    public function create(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $repairs = $branch->repairOrders()
            ->with(['vehicle', 'garageCustomer.user'])
            ->get();

        return view('garage.warranty-claims.create', [
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
            'customer_id' => ['required', 'exists:garage_customers,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'warranty_provider' => ['nullable', 'string', 'max:255'],
            'warranty_reference' => ['nullable', 'string', 'max:255'],
            'warranty_expiry_date' => ['nullable', 'date'],
            'claim_description' => ['required', 'string', 'max:2000'],
            'claim_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $claim = WarrantyClaim::create($request->validated());

        $this->auditService->log(WarrantyClaim::class, $claim->id, 'created', null, $claim->toArray());

        return redirect()->route('garage.warranty-claims.index')->with('success', 'Réclamation créée avec succès.');
    }

    public function edit(Request $request, $claimId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $claim = WarrantyClaim::where('branch_id', $branch->id)->findOrFail($claimId);
        $repairs = $branch->repairOrders()->with(['vehicle', 'garageCustomer.user'])->get();

        return view('garage.warranty-claims.edit', [
            'branch' => $branch,
            'claim' => $claim,
            'repairs' => $repairs,
        ]);
    }

    public function update(Request $request, $claimId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $claim = WarrantyClaim::where('branch_id', $branch->id)->findOrFail($claimId);

        $request->validate([
            'repair_order_id' => ['required', 'exists:repair_orders,id'],
            'customer_id' => ['required', 'exists:garage_customers,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'warranty_provider' => ['nullable', 'string', 'max:255'],
            'warranty_reference' => ['nullable', 'string', 'max:255'],
            'warranty_expiry_date' => ['nullable', 'date'],
            'claim_description' => ['required', 'string', 'max:2000'],
            'claim_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'string', 'in:PENDING,APPROVED,REJECTED,RESOLVED'],
        ]);

        $oldValues = $claim->toArray();
        $claim->update($request->validated());

        $this->auditService->log(WarrantyClaim::class, $claim->id, 'updated', $oldValues, $claim->toArray());

        return redirect()->route('garage.warranty-claims.index')->with('success', 'Réclamation mise à jour avec succès.');
    }

    public function destroy(Request $request, $claimId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $claim = WarrantyClaim::where('branch_id', $branch->id)->findOrFail($claimId);
        $claim->delete();

        return redirect()->route('garage.warranty-claims.index')->with('success', 'Réclamation supprimée avec succès.');
    }
}
