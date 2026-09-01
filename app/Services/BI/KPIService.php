<?php

namespace App\Services\BI;

use App\Models\BI\KPI;
use App\Models\BI\Metric;
use Illuminate\Support\Facades\DB;

class KPIService
{
    /**
     * Calculate and store a metric for a given KPI
     */
    public function calculateMetric(string $kpiCode, string $period = 'monthly')
    {
        $kpi = KPI::where('code', $kpiCode)->firstOrFail();

        // Business logic for different KPIs
        $value = 0;
        switch ($kpiCode) {
            case 'monthly_revenue':
                $value = DB::table('payment_transactions')
                    ->where('status', 'SUCCESS')
                    ->where('created_at', '>=', now()->startOfMonth())
                    ->sum('amount');
                break;
            case 'avg_repair_time':
                $value = DB::table('repair_orders')
                    ->where('status', 'COMPLETED')
                    ->avg(DB::raw('TIMESTAMPDIFF(MINUTE, opened_at, completed_at)')) ?? 0;
                break;
        }

        return Metric::create([
            'kpi_id' => $kpi->id,
            'value' => $value,
            'period' => $period,
            'calculated_at' => now(),
        ]);
    }
}
