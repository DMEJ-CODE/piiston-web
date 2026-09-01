<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\GarageBranch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manage', $branch->company);

        $branches = $branch->company->branches()->with('manager')->get();

        return view('garage.branches.index', [
            'branches' => $branches,
            'branch' => $branch,
        ]);
    }

    public function create(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manage', $branch->company);

        $employees = $branch->company->branches()
            ->with('employees.user')
            ->get()
            ->pluck('employees')
            ->flatten()
            ->unique('user_id');

        return view('garage.branches.create', [
            'branch' => $branch,
            'employees' => $employees,
        ]);
    }

    public function store(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manage', $branch->company);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'manager_id' => 'nullable|exists:users,id',
            'address_id' => 'nullable|exists:addresses,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $branch->company->branches()->create($request->all() + ['status' => true]);

        return redirect()->route('garage.branches.index')->with('success', 'Nouvelle branche créée avec succès.');
    }

    public function edit(Request $request, GarageBranch $targetBranch)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manage', $branch->company);

        return view('garage.branches.edit', [
            'branch' => $branch,
            'targetBranch' => $targetBranch,
        ]);
    }

    public function update(Request $request, GarageBranch $targetBranch)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manage', $branch->company);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $targetBranch->update($request->all());

        return redirect()->route('garage.branches.index')->with('success', 'Branche mise à jour.');
    }
}
