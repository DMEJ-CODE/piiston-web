<?php

namespace App\Services\BI;

use App\Models\BI\AnalyticsSnapshot;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Aggregate data into a snapshot for dashboards
     */
    public function createSnapshot(string $type)
    {
        $data = [];
        switch ($type) {
            case 'revenue_by_country':
                $data = DB::table('payment_transactions')
                    ->join('currencies', 'payment_transactions.currency_id', '=', 'currencies.id')
                    ->select('currencies.code as currency', DB::raw('SUM(amount) as total'))
                    ->groupBy('currencies.code')
                    ->get();
                break;
        }

        return AnalyticsSnapshot::create([
            'snapshot_type' => $type,
            'data' => $data,
            'generated_at' => now(),
        ]);
    }
}
