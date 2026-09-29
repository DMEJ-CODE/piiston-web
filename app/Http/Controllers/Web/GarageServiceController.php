<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Garages\GarageService;
use Illuminate\Http\Request;

class GarageServiceController extends Controller
{
    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $query = $branch->services();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $services = $query->paginate(15)->withQueryString();

        return view('garage.services.index', [
            'services' => $services,
            'branch' => $branch,
        ]);
    }

    public function store(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
        ]);

        $branch->services()->create($request->all());

        return back()->with('success', 'Service ajouté au catalogue.');
    }

    public function update(Request $request, GarageService $service)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);
        abort_unless($service->branch_id === $branch->id, 404);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
        ]);

        $service->update($request->all());

        return back()->with('success', 'Service mis à jour.');
    }

    public function destroy(Request $request, GarageService $service)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);
        abort_unless($service->branch_id === $branch->id, 404);

        $service->delete();

        return back()->with('success', 'Service retiré du catalogue.');
    }
}
