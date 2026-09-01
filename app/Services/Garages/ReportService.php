<?php

namespace App\Services\Garages;

use App\Models\Garages\GarageAppointment;
use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageInvoice;
use App\Models\Garages\RepairOrder;
use App\Models\Garages\RepairPartUsage;
use App\Models\Garages\WorkshopBay;
use App\Models\Workflows\EmergencyRequest;

class ReportService
{
    public function getBranchDashboard(GarageBranch $branch): array
    {
        $now = now();
        $monthStart = $now->copy()->startOfMonth();

        $repairsThisMonth = RepairOrder::where('branch_id', $branch->id)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $revenueThisMonth = GarageInvoice::whereHas('repairOrder', function ($q) use ($branch) {
            $q->where('branch_id', $branch->id);
        })
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_payable');

        $pendingAppointments = GarageAppointment::where('branch_id', $branch->id)
            ->whereIn('status', ['REQUESTED', 'CONFIRMED'])
            ->whereDate('scheduled_date', '>=', $now->toDateString())
            ->count();

        $lowStockItems = app(InventoryService::class)->getLowStockItems($branch)->count();

        $activeRepairs = RepairOrder::where('branch_id', $branch->id)
            ->whereIn('status', [
                RepairOrder::STATUS_IN_PROGRESS,
                RepairOrder::STATUS_WAITING_PARTS,
                RepairOrder::STATUS_DIAGNOSIS,
            ])
            ->count();

        $urgentEmergencies = EmergencyRequest::where('status', 'REQUESTED')
            ->count(); // In a real multi-tenant scenario, this would be filtered by location/nearby garages

        $totalBays = WorkshopBay::where('branch_id', $branch->id)->sum('capacity');
        $occupiedBays = RepairOrder::where('branch_id', $branch->id)
            ->whereNotNull('workshop_bay_id')
            ->whereIn('status', [RepairOrder::STATUS_IN_PROGRESS, RepairOrder::STATUS_DIAGNOSIS])
            ->count();
        $occupationRate = $totalBays > 0 ? ($occupiedBays / $totalBays) * 100 : 0;

        return [
            'repairs_this_month' => $repairsThisMonth,
            'revenue_this_month' => (float) $revenueThisMonth,
            'pending_appointments' => $pendingAppointments,
            'low_stock_items' => $lowStockItems,
            'active_repairs' => $activeRepairs,
            'urgent_emergencies' => $urgentEmergencies,
            'occupation_rate' => round($occupationRate, 1),
        ];
    }

    public function getMechanicPerformance(GarageBranch $branch): array
    {
        $mechanics = $branch->employees()
            ->whereIn('position', ['Mechanic', 'Diagnostic Technician'])
            ->with('user')
            ->get();

        $performance = [];

        foreach ($mechanics as $mechanic) {
            $completedRepairs = RepairOrder::where('branch_id', $branch->id)
                ->where('assigned_mechanic_id', $mechanic->user_id)
                ->where('status', RepairOrder::STATUS_COMPLETED)
                ->count();

            $avgTime = RepairOrder::where('branch_id', $branch->id)
                ->where('assigned_mechanic_id', $mechanic->user_id)
                ->where('status', RepairOrder::STATUS_COMPLETED)
                ->whereNotNull('opened_at')
                ->whereNotNull('closed_at')
                ->get()
                ->map(function ($ro) {
                    return $ro->closed_at->diffInHours($ro->opened_at);
                })
                ->avg() ?? 0;

            $performance[] = [
                'name' => $mechanic->user->name,
                'completed' => $completedRepairs,
                'avg_hours' => round($avgTime, 1),
            ];
        }

        return $performance;
    }

    public function getMonthlyFinancialBreakdown(GarageBranch $branch, $month = null, $year = null): array
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $revenue = GarageInvoice::whereHas('repairOrder', function ($q) use ($branch) {
            $q->where('branch_id', $branch->id);
        })
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('total_payable');

        // Logic for parts cost (expenses)
        $partsCost = RepairPartUsage::whereHas('repairOrder', function ($q) use ($branch) {
            $q->where('branch_id', $branch->id);
        })
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get()
            ->sum(function ($usage) {
                // In a real scenario, we would link back to the inventory's cost_price
                return $usage->quantity * ($usage->unit_price * 0.6); // Simple assumption 40% margin
            });

        return [
            'revenue' => (float) $revenue,
            'expenses' => (float) $partsCost,
            'profit' => (float) ($revenue - $partsCost),
        ];
    }
}
