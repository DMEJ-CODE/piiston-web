<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Fleets\FleetExpense;
use App\Models\Fleets\FuelRecord;
use App\Models\Garages\GarageInvoice;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $period = $request->query('period', 'month'); // month, year, all

        $queryStart = now()->startOfMonth();
        if ($period === 'year') {
            $queryStart = now()->startOfYear();
        } elseif ($period === 'all') {
            $queryStart = Carbon::parse('2000-01-01');
        }

        // 1. Fuel Expenses
        $fuelSpending = FuelRecord::whereHas('vehicle', function ($q) use ($userId) {
            $q->where('owner_id', $userId);
        })
            ->where('date', '>=', $queryStart)
            ->sum('total_price');

        // 2. Maintenance/Repair Expenses
        $repairSpending = GarageInvoice::where('customer_id', $userId) // Assuming user_id maps to customer_id or linked
            ->where('created_at', '>=', $queryStart)
            ->where('status', 'PAID')
            ->sum('total_payable');

        // 3. Other Fleet Expenses (Insurance, etc)
        $otherSpending = FleetExpense::whereHas('vehicle', function ($q) use ($userId) {
            $q->where('owner_id', $userId);
        })
            ->where('expense_date', '>=', $queryStart)
            ->sum('amount');

        return response()->json([
            'summary' => [
                'total' => $fuelSpending + $repairSpending + $otherSpending,
                'fuel' => (float) $fuelSpending,
                'maintenance' => (float) $repairSpending,
                'other' => (float) $otherSpending,
            ],
            'period' => $period,
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        // Detailed list of transactions
        // For brevity in this phase, we'll return a stub or combined collection
        return response()->json(['message' => 'Detailed history coming soon']);
    }
}
