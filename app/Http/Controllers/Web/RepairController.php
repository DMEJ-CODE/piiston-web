<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\RepairOrder;
use App\Models\Vehicles\Vehicle;
use App\Services\Workflows\RepairService;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = RepairOrder::where('branch_id', $branch->id)
            ->with(['vehicle', 'garageCustomer.user', 'mechanic', 'bay']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('vehicle', function ($vq) use ($search) {
                    $vq->where('license_plate', 'like', "%{$search}%")
                        ->orWhere('vin', 'like', "%{$search}%");
                })->orWhereHas('garageCustomer.user', function ($uq) use ($search) {
                    $uq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $repairs = $query->latest()->paginate(15)->withQueryString();

        return view('garage.repairs.index', [
            'repairs' => $repairs,
            'branch' => $branch,
        ]);
    }

    public function create(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $customers = $branch->customers()->with('user')->get();
        $vehicles = Vehicle::whereHas('owner', function ($q) use ($branch) {
            $q->whereIn('id', $branch->customers()->whereNotNull('user_id')->pluck('user_id'));
        })->get();
        $mechanics = $branch->employees()->where('position', 'MECHANIC')->with('user')->get();
        $bays = $branch->bays()->get();

        return view('garage.repairs.create', [
            'branch' => $branch,
            'customers' => $customers,
            'vehicles' => $vehicles,
            'mechanics' => $mechanics,
            'bays' => $bays,
        ]);
    }

    public function store(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:garage_customers,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'problem_description' => ['required', 'string', 'max:2000'],
            'priority' => ['nullable', 'string', 'in:LOW,MEDIUM,HIGH,URGENT'],
            'assigned_mechanic_id' => ['nullable', 'exists:users,id'],
            'workshop_bay_id' => ['nullable', 'exists:workshop_bays,id'],
        ]);

        $repair = RepairOrder::create(array_merge($validated, [
            'branch_id' => $branch->id,
            'status' => 'REQUESTED',
        ]));

        return redirect()->route('garage.repairs.show', $repair->id)->with('success', 'Réparation créée avec succès.');
    }

    public function show(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)
            ->with(['vehicle', 'garageCustomer.user', 'mechanic', 'bay', 'diagnosis', 'tasks', 'parts', 'estimate', 'invoice', 'progress'])
            ->findOrFail($repairId);

        $mechanics = $branch->employees()->where('position', 'MECHANIC')->with('user')->get();
        $bays = $branch->bays()->get();

        return view('garage.repairs.show', [
            'repair' => $repair,
            'branch' => $branch,
            'mechanics' => $mechanics,
            'bays' => $bays,
        ]);
    }

    public function updateStatus(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)->findOrFail($repairId);

        $request->validate([
            'status' => ['required', 'string', 'in:REQUESTED,IN_PROGRESS,DIAGNOSIS,WAITING_PARTS,ESTIMATE_PENDING,ESTIMATE_APPROVED,COMPLETED,DELIVERED'],
        ]);

        $repair->update(['status' => $request->status]);

        return back()->with('success', 'Statut mis à jour.');
    }

    public function assignMechanic(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)->findOrFail($repairId);

        $request->validate([
            'assigned_mechanic_id' => ['nullable', 'exists:users,id'],
        ]);

        $repair->update(['assigned_mechanic_id' => $request->assigned_mechanic_id]);

        return back()->with('success', 'Mécanicien assigné.');
    }

    public function assignBay(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)->findOrFail($repairId);

        $request->validate([
            'workshop_bay_id' => ['nullable', 'exists:workshop_bays,id'],
        ]);

        $repair->update(['workshop_bay_id' => $request->workshop_bay_id]);

        return back()->with('success', 'Bay assignée.');
    }

    public function addTask(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)->findOrFail($repairId);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'estimated_time_minutes' => ['nullable', 'integer', 'min:0'],
            'assigned_employee_id' => ['nullable', 'exists:garage_employees,id'],
        ]);

        app(RepairService::class)->addTask($repair, $request->all());

        return back()->with('success', 'Tâche ajoutée.');
    }

    public function addPart(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)->findOrFail($repairId);

        $request->validate([
            'part_id' => ['nullable', 'exists:garage_inventory,id'],
            'part_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        $data = $request->all();
        $data['total_price'] = $request->quantity * $request->unit_price;

        app(RepairService::class)->addPartUsage($repair, $data);

        return back()->with('success', 'Pièce ajoutée.');
    }

    public function generateInvoice(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)->findOrFail($repairId);

        app(RepairService::class)->generateInvoice($repair);

        return back()->with('success', 'Facture générée.');
    }

    public function printInvoice(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)
            ->with(['invoice', 'vehicle', 'garageCustomer.user', 'parts', 'tasks', 'checkIn'])
            ->findOrFail($repairId);

        if (! $repair->invoice) {
            return back()->with('error', 'Aucune facture générée pour cette réparation.');
        }

        return view('garage.documents.invoice-print', [
            'repair' => $repair,
            'invoice' => $repair->invoice,
            'branch' => $branch,
        ]);
    }

    public function recordPayment(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)->findOrFail($repairId);

        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'string', 'in:CASH,MOBILE_MONEY,CARD,BANK_TRANSFER'],
            'transaction_reference' => ['nullable', 'string', 'max:100'],
        ]);

        app(RepairService::class)->recordPayment($repair, $request->all());

        return back()->with('success', 'Paiement enregistré.');
    }
}
