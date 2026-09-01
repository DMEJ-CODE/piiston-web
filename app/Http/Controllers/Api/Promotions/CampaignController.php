<?php

namespace App\Http\Controllers\Api\Promotions;

use App\Http\Controllers\Controller;
use App\Models\Promotions\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index()
    {
        // Fetch campaigns for the current professional (Garage, Seller, etc.)
        return response()->json(
            Campaign::where('owner_id', Auth::id())->with(['advertisements', 'budgetDetails'])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'objective' => 'required|string',
            'budget' => 'required|numeric|min:1000',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $campaign = Campaign::create($validated + [
            'owner_type' => 'User', // Simplified for this context
            'owner_id' => Auth::id(),
            'status' => 'draft',
        ]);

        $campaign->budgetDetails()->create([
            'total_budget' => $validated['budget'],
            'remaining_amount' => $validated['budget'],
        ]);

        return response()->json($campaign, 201);
    }
}
