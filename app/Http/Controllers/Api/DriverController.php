<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fleets\Company;

class DriverController extends Controller
{
    public function index(Company $company)
    {
        return response()->json($company->drivers()->with('user')->get());
    }
}
