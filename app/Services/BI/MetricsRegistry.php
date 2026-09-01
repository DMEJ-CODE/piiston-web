<?php

namespace App\Services\BI;

class MetricsRegistry
{
    /**
     * Central repository of KPI definitions
     */
    public static function getDefinitions(): array
    {
        return [
            'monthly_revenue' => [
                'name' => 'Monthly Revenue',
                'formula' => 'sum(payment_transactions.amount) where status=SUCCESS',
                'unit' => 'XAF',
                'category' => 'FINANCE',
            ],
            'repair_conversion_rate' => [
                'name' => 'Repair Conversion',
                'formula' => 'count(repair_orders) / count(service_requests)',
                'unit' => '%',
                'category' => 'OPERATIONS',
            ],
            'fleet_utilization' => [
                'name' => 'Fleet Utilization',
                'formula' => 'count(active_trips) / count(fleet_vehicles)',
                'unit' => '%',
                'category' => 'FLEET',
            ],
        ];
    }
}
