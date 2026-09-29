<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\VehicleCheckIn;
use App\Models\Vehicles\Vehicle;
use App\Services\Garages\ReceptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CheckInController extends Controller
{
    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = VehicleCheckIn::where('branch_id', $branch->id)
            ->with(['customer.user', 'vehicle', 'appointment']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('vehicle', function ($q) use ($search) {
                $q->where('license_plate', 'like', "%{$search}%");
            });
        }

        $checkIns = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('garage.check-ins.index', [
            'checkIns' => $checkIns,
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

        return view('garage.check-ins.create', [
            'branch' => $branch,
            'customers' => $customers,
            'vehicles' => $vehicles,
        ]);
    }

    public function store(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('operate', $branch);

        $data = $request->validate([
            'customer_id' => ['required', 'exists:garage_customers,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'appointment_id' => ['nullable', 'exists:garage_appointments,id'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'fuel_level' => ['nullable', 'string', 'max:50'],
            'vehicle_condition' => ['nullable', 'string', 'max:2000'],
            'checklist' => ['nullable', 'array'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'create_repair_order' => ['nullable', 'boolean'],
            'signature_data' => ['nullable', 'string'],
        ]);

        // Handle signature if provided (save to storage)
        if ($request->filled('signature_data')) {
            $signature = $request->signature_data;
            $signature = str_replace('data:image/png;base64,', '', $signature);
            $signature = str_replace(' ', '+', $signature);
            $imageName = 'sig_'.time().'.png';
            Storage::disk('public')->put('signatures/'.$imageName, base64_decode($signature));
            $data['signature_path'] = 'signatures/'.$imageName;
        }

        app(ReceptionService::class)->completeReception($branch, $data);

        return redirect()->route('garage.check-ins.index')->with('success', 'Véhicule enregistré avec succès.');
    }

    public function edit(Request $request, $checkInId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $checkIn = VehicleCheckIn::where('branch_id', $branch->id)->findOrFail($checkInId);

        $customers = $branch->customers()->with('user')->get();
        $vehicles = Vehicle::whereHas('owner', function ($q) use ($branch) {
            $q->whereIn('id', $branch->customers()->whereNotNull('user_id')->pluck('user_id'));
        })->get();

        return view('garage.check-ins.edit', [
            'branch' => $branch,
            'checkIn' => $checkIn,
            'customers' => $customers,
            'vehicles' => $vehicles,
        ]);
    }

    public function update(Request $request, $checkInId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $checkIn = VehicleCheckIn::where('branch_id', $branch->id)->findOrFail($checkInId);

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:garage_customers,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'appointment_id' => ['nullable', 'exists:garage_appointments,id'],
            'mileage' => ['nullable', 'integer', 'min:0'],
            'fuel_level' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $checkIn->update($validated);

        return redirect()->route('garage.check-ins.index')->with('success', 'Enregistrement mis à jour avec succès.');
    }

    public function destroy(Request $request, $checkInId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $checkIn = VehicleCheckIn::where('branch_id', $branch->id)->findOrFail($checkInId);
        $checkIn->delete();

        return redirect()->route('garage.check-ins.index')->with('success', 'Enregistrement supprimé avec succès.');
    }
}
