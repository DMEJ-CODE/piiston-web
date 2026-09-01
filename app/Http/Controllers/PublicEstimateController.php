<?php

namespace App\Http\Controllers;

use App\Models\Garages\RepairEstimate;
use App\Models\Garages\RepairOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicEstimateController extends Controller
{
    public function show($token)
    {
        $estimate = RepairEstimate::where('public_token', $token)
            ->where('token_expires_at', '>', now())
            ->with(['repairOrder.vehicle', 'repairOrder.parts', 'repairOrder.tasks', 'repairOrder.branch'])
            ->firstOrFail();

        return view('public.estimates.show', [
            'estimate' => $estimate,
            'repair' => $estimate->repairOrder,
            'branch' => $estimate->repairOrder->branch,
        ]);
    }

    public function approve($token)
    {
        $estimate = RepairEstimate::where('public_token', $token)->firstOrFail();

        $repair = $estimate->repairOrder;
        $repair->update(['status' => RepairOrder::STATUS_APPROVED]);
        $estimate->update(['status' => 'APPROVED', 'public_token' => null]);

        return back()->with('success', 'Devis approuvé avec succès. L\'atelier a été notifié.');
    }

    public function generateToken(Request $request, $estimateId)
    {
        $estimate = RepairEstimate::findOrFail($estimateId);
        $token = Str::random(40);

        $estimate->update([
            'public_token' => $token,
            'token_expires_at' => now()->addDays(7),
        ]);

        return back()->with('success', 'Lien de partage généré : '.route('public.estimate.show', $token));
    }
}
