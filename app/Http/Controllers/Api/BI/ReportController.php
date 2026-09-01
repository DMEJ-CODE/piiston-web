<?php

namespace App\Http\Controllers\Api\BI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        return response()->json(
            Auth::user()->reports()->orderBy('generated_at', 'desc')->paginate(20)
        );
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'report_type' => 'required|string',
        ]);

        $report = Auth::user()->reports()->create($validated + [
            'generated_at' => now(),
            'generated_by' => Auth::id(),
            'owner_type' => 'User',
            'owner_id' => Auth::id(),
        ]);

        return response()->json($report, 201);
    }
}
