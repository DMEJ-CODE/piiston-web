<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\GarageEmployee;
use App\Models\Garages\GarageInvitation;
use App\Services\Garages\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GarageEmployeeController extends Controller
{
    public function __construct(protected EmployeeService $employeeService) {}

    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = GarageEmployee::where('branch_id', $branch->id)->with(['user', 'department']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(15)->withQueryString();

        $invitations = GarageInvitation::where('branch_id', $branch->id)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->get();

        return view('garage.employees.index', [
            'employees' => $employees,
            'invitations' => $invitations,
            'branch' => $branch,
        ]);
    }

    public function create(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        return view('garage.employees.create', [
            'branch' => $branch,
        ]);
    }

    public function store(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $request->validate([
            'email' => ['required', 'email'],
            'role' => ['required', 'string', 'in:MANAGER,MECHANIC,RECEPTIONIST,ACCOUNTANT'],
        ]);

        GarageInvitation::create([
            'branch_id' => $branch->id,
            'email' => $request->email,
            'role' => $request->role,
            'token' => Str::random(40),
            'expires_at' => now()->addDays(7),
        ]);

        return redirect()->route('garage.employees.index')->with('success', 'Invitation envoyée à '.$request->email);
    }

    public function edit(Request $request, $employeeId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $employee = GarageEmployee::where('branch_id', $branch->id)
            ->with(['user', 'department'])
            ->findOrFail($employeeId);

        return view('garage.employees.edit', [
            'branch' => $branch,
            'employee' => $employee,
        ]);
    }

    public function update(Request $request, $employeeId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $employee = GarageEmployee::where('branch_id', $branch->id)->findOrFail($employeeId);

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'department_id' => ['nullable', 'exists:garage_departments,id'],
            'employee_number' => ['nullable', 'string', 'max:50', 'unique:garage_employees,employee_number,'.$employee->id],
            'position' => ['nullable', 'string', 'max:100'],
            'hire_date' => ['nullable', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
        ]);

        $this->employeeService->updateEmployee($employee, $request->validated());

        return redirect()->route('garage.employees.index')->with('success', 'Employé mis à jour avec succès.');
    }

    public function destroy(Request $request, $employeeId)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $employee = GarageEmployee::where('branch_id', $branch->id)->findOrFail($employeeId);
        $this->employeeService->terminateEmployee($employee);

        return redirect()->route('garage.employees.index')->with('success', 'Employé supprimé avec succès.');
    }
}
