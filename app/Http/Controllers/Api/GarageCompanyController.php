<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Garages\GarageCompany;
use Illuminate\Support\Facades\Auth;

class GarageCompanyController extends Controller
{
    public function index()
    {
        return response()->json(
            Auth::user()->garageCompanies ?? GarageCompany::where('owner_id', Auth::id())->with('branches')->get()
        );
    }

    public function show(GarageCompany $company)
    {
        return response()->json($company->load(['branches.departments', 'branches.employees.user']));
    }
}
