<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Workflows\EmergencyRequest;
use Illuminate\Http\Request;

class GarageEmergencyController extends Controller
{
    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $emergencies = EmergencyRequest::where(function ($query) use ($branch) {
            $query->where('status', 'REQUESTED')
                ->orWhere('branch_id', $branch->id);
        })
            ->whereIn('status', ['REQUESTED', 'ACCEPTED', 'IN_PROGRESS', 'ARRIVED'])
            ->with(['user', 'vehicle', 'mechanic'])
            ->latest()
            ->paginate(15);

        $branch->load('employees.user');

        return view('garage.emergencies.index', [
            'emergencies' => $emergencies,
            'branch' => $branch,
        ]);
    }

    public function accept(Request $request, $id)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $emergency = EmergencyRequest::findOrFail($id);

        $validated = $request->validate([
            'mechanic_id' => 'nullable|exists:users,id',
        ]);

        $mechanicId = $validated['mechanic_id'] ?? $request->user()->id;

        $emergency->update([
            'status' => 'ACCEPTED',
            'branch_id' => $branch->id,
            'assigned_mechanic_id' => $mechanicId,
        ]);

        return back()->with('success', 'Urgence acceptée et assignée.');
    }
}
