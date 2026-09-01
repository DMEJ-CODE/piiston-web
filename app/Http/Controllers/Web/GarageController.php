<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\GarageAppointment;
use App\Models\Garages\GarageCustomer;
use App\Models\Garages\GarageInvoice;
use App\Models\Garages\RepairOrder;
use App\Models\Garages\RepairPart;
use App\Models\Vehicles\Vehicle;
use App\Services\Garages\AppointmentService;
use App\Services\Garages\CustomerService;
use App\Services\Garages\ReportService;
use App\Services\Garages\SubscriptionService;
use Illuminate\Http\Request;

class GarageController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected SubscriptionService $subscriptionService,
        protected AppointmentService $appointmentService,
        protected CustomerService $customerService
    ) {}

    public function dashboard(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        // Multi-branch logic: redirect to general if no branch specified and has_annexes is true
        if ($branch->company->has_annexes && ! session()->has('active_garage_branch_id') && ! request()->routeIs('garage.branches.*')) {
            return redirect()->route('garage.dashboard', ['mode' => 'general']);
        }

        if ($request->query('mode') === 'general') {
            return $this->generalDashboard($request, $branch->company);
        }

        $stats = $this->reportService->getBranchDashboard($branch);
        $subscription = $this->subscriptionService->getActiveSubscription($branch);

        // Fetch recent repairs for the activity list
        $recentRepairs = $branch->repairOrders()
            ->with(['vehicle', 'garageCustomer.user'])
            ->latest()
            ->limit(5)
            ->get();

        // Fetch daily revenue for the last 14 days for the chart
        $revenueData = GarageInvoice::whereHas('repairOrder', function ($q) use ($branch) {
            $q->where('branch_id', $branch->id);
        })
            ->where('created_at', '>=', now()->subDays(14))
            ->selectRaw('DATE(created_at) as date, SUM(total_payable) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date');

        return view('garage.dashboard', [
            'stats' => $stats,
            'subscription' => $subscription,
            'branch' => $branch,
            'recentRepairs' => $recentRepairs,
            'revenueData' => $revenueData,
        ]);
    }

    public function generalDashboard(Request $request, $company)
    {
        $this->authorize('manage', $company);

        $stats = $this->reportService->getCompanyDashboard($company);

        return view('garage.general-dashboard', [
            'stats' => $stats,
            'company' => $company,
            'branches' => $company->branches()->with('manager')->get(),
        ]);
    }

    public function customers(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = $branch->customers()->with('user')->withCount('repairOrders');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('customer_type', $request->type);
        }

        $customers = $query->paginate(15)->withQueryString();

        return view('garage.customers.index', [
            'customers' => $customers,
            'branch' => $branch,
        ]);
    }

    public function createCustomer(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        return view('garage.customers.create', [
            'branch' => $branch,
        ]);
    }

    public function storeCustomer(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'customer_type' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->customerService->createCustomer($branch, $request->validated());

        return redirect()->route('garage.customers.index')->with('success', 'Client créé avec succès.');
    }

    public function editCustomer(Request $request, $customerId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $customer = GarageCustomer::where('branch_id', $branch->id)->findOrFail($customerId);

        return view('garage.customers.edit', [
            'branch' => $branch,
            'customer' => $customer,
        ]);
    }

    public function updateCustomer(Request $request, $customerId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $customer = GarageCustomer::where('branch_id', $branch->id)->findOrFail($customerId);

        $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'customer_type' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->customerService->updateCustomer($customer, $request->validated());

        return redirect()->route('garage.customers.index')->with('success', 'Client mis à jour avec succès.');
    }

    public function destroyCustomer(Request $request, $customerId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $customer = GarageCustomer::where('branch_id', $branch->id)->findOrFail($customerId);
        $this->customerService->deleteCustomer($customer);

        return redirect()->route('garage.customers.index')->with('success', 'Client supprimé avec succès.');
    }

    public function appointments(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = $branch->appointments()->with(['customer.user', 'vehicle']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer.user', function ($uq) use ($search) {
                    $uq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                })->orWhereHas('vehicle', function ($vq) use ($search) {
                    $vq->where('license_plate', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('scheduled_date', 'asc')->paginate(15)->withQueryString();

        return view('garage.appointments.index', [
            'appointments' => $appointments,
            'branch' => $branch,
        ]);
    }

    public function createAppointment(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $customers = $branch->customers()->with('user')->get();
        $vehicles = Vehicle::whereHas('owner', function ($q) use ($branch) {
            $q->whereIn('id', $branch->customers()->whereNotNull('user_id')->pluck('user_id'));
        })->get();

        return view('garage.appointments.create', [
            'branch' => $branch,
            'customers' => $customers,
            'vehicles' => $vehicles,
        ]);
    }

    public function storeAppointment(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $request->validate([
            'customer_id' => ['required', 'exists:garage_customers,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'scheduled_date' => ['required', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:15'],
        ]);

        $this->appointmentService->createAppointment($branch, $request->validated());

        return redirect()->route('garage.appointments.index')->with('success', 'Rendez-vous créé avec succès.');
    }

    public function editAppointment(Request $request, $appointmentId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $appointment = GarageAppointment::where('branch_id', $branch->id)->findOrFail($appointmentId);

        $customers = $branch->customers()->with('user')->get();
        $vehicles = Vehicle::whereHas('owner', function ($q) use ($branch) {
            $q->whereIn('id', $branch->customers()->whereNotNull('user_id')->pluck('user_id'));
        })->get();

        return view('garage.appointments.edit', [
            'branch' => $branch,
            'appointment' => $appointment,
            'customers' => $customers,
            'vehicles' => $vehicles,
        ]);
    }

    public function updateAppointment(Request $request, $appointmentId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $appointment = GarageAppointment::where('branch_id', $branch->id)->findOrFail($appointmentId);

        $request->validate([
            'customer_id' => ['required', 'exists:garage_customers,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'scheduled_date' => ['required', 'date'],
            'duration_minutes' => ['nullable', 'integer', 'min:15'],
        ]);

        $this->appointmentService->updateAppointment($appointment, $request->validated());

        return redirect()->route('garage.appointments.index')->with('success', 'Rendez-vous mis à jour avec succès.');
    }

    public function destroyAppointment(Request $request, $appointmentId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $appointment = GarageAppointment::where('branch_id', $branch->id)->findOrFail($appointmentId);
        $this->appointmentService->cancelAppointment($appointment);

        return redirect()->route('garage.appointments.index')->with('success', 'Rendez-vous annulé avec succès.');
    }

    public function repairs(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $repairs = $branch->repairOrders()
            ->with(['vehicle', 'garageCustomer.user', 'mechanic'])
            ->paginate(15);

        return view('garage.repairs.index', [
            'repairs' => $repairs,
            'branch' => $branch,
        ]);
    }

    public function showRepair(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)
            ->with(['vehicle', 'garageCustomer.user', 'mechanic', 'diagnoses', 'tasks', 'estimate', 'invoice'])
            ->findOrFail($repairId);

        return view('garage.repairs.show', [
            'repair' => $repair,
            'branch' => $branch,
        ]);
    }

    public function updateRepairStatus(Request $request, $repairId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $repair = RepairOrder::where('branch_id', $branch->id)->findOrFail($repairId);

        $request->validate([
            'status' => ['required', 'string', 'in:REQUESTED,IN_PROGRESS,DIAGNOSIS,WAITING_PARTS,ESTIMATE_PENDING,ESTIMATE_APPROVED,COMPLETED,DELIVERED'],
        ]);

        $oldStatus = $repair->status;
        $repair->update(['status' => $request->status]);

        return back()->with('success', 'Statut mis à jour avec succès.');
    }

    public function advancedReports(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $performance = $this->reportService->getMechanicPerformance($branch);
        $finance = $this->reportService->getMonthlyFinancialBreakdown($branch);

        return view('garage.reports.advanced', [
            'branch' => $branch,
            'performance' => $performance,
            'finance' => $finance,
        ]);
    }

    public function inventory(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = RepairPart::where('branch_id', $branch->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('part_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $parts = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('garage.inventory.index', [
            'parts' => $parts,
            'branch' => $branch,
        ]);
    }

    public function createInventory(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        return view('garage.inventory.create', [
            'branch' => $branch,
        ]);
    }

    public function storeInventory(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $request->validate([
            'part_number' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'compatible_vehicles' => ['nullable', 'string', 'max:500'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
            'maximum_stock' => ['nullable', 'integer', 'min:0'],
            'storage_location' => ['nullable', 'string', 'max:100'],
        ]);

        RepairPart::create(array_merge($request->validated(), ['branch_id' => $branch->id]));

        return redirect()->route('garage.inventory.index')->with('success', 'Pièce créée avec succès.');
    }

    public function editInventory(Request $request, $partId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $part = RepairPart::where('branch_id', $branch->id)->findOrFail($partId);

        return view('garage.inventory.edit', [
            'branch' => $branch,
            'part' => $part,
        ]);
    }

    public function updateInventory(Request $request, $partId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $part = RepairPart::where('branch_id', $branch->id)->findOrFail($partId);

        $request->validate([
            'part_number' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'compatible_vehicles' => ['nullable', 'string', 'max:500'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
            'maximum_stock' => ['nullable', 'integer', 'min:0'],
            'storage_location' => ['nullable', 'string', 'max:100'],
        ]);

        $part->update($request->validated());

        return redirect()->route('garage.inventory.index')->with('success', 'Pièce mise à jour avec succès.');
    }

    public function destroyInventory(Request $request, $partId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $part = RepairPart::where('branch_id', $branch->id)->findOrFail($partId);
        $part->delete();

        return redirect()->route('garage.inventory.index')->with('success', 'Pièce supprimée avec succès.');
    }

    public function reports(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $now = now();
        $monthStart = $now->copy()->startOfMonth();

        $repairsThisMonth = RepairOrder::where('branch_id', $branch->id)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $revenueThisMonth = GarageInvoice::whereHas('repairOrder', function ($q) use ($branch) {
            $q->where('branch_id', $branch->id);
        })
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_payable');

        $revenueByDay = GarageInvoice::whereHas('repairOrder', function ($q) use ($branch) {
            $q->where('branch_id', $branch->id);
        })
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->selectRaw('DATE(created_at) as date, SUM(total_payable) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $repairsByStatus = RepairOrder::where('branch_id', $branch->id)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $topCustomers = GarageCustomer::where('branch_id', $branch->id)
            ->withSum(['repairOrders as total_revenue' => function ($q) use ($monthStart) {
                $q->whereHas('invoice', function ($iq) use ($monthStart) {
                    $iq->whereMonth('created_at', $monthStart->month)
                        ->whereYear('created_at', $monthStart->year);
                });
            }], 'final_cost')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        return view('garage.reports.index', [
            'branch' => $branch,
            'repairsThisMonth' => $repairsThisMonth,
            'revenueThisMonth' => (float) $revenueThisMonth,
            'revenueByDay' => $revenueByDay,
            'repairsByStatus' => $repairsByStatus,
            'topCustomers' => $topCustomers,
        ]);
    }
}
