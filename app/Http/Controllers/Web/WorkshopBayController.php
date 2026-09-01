<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WorkshopBayController extends Controller
{
    public function index(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $bays = $branch->bays()->get();

        return view('garage.bays.index', [
            'bays' => $bays,
            'branch' => $branch,
        ]);
    }

    public function store(Request $request)
    {
        $branch = $request->attributes->get('garageBranch');
        $this->authorize('manageBranch', $branch);

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        $branch->bays()->create($request->all());

        return back()->with('success', 'Emplacement (Bay) ajouté.');
    }
}
