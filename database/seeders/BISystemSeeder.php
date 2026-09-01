<?php

namespace Database\Seeders;

use App\Models\BI\Dashboard;
use App\Models\BI\DashboardWidget;
use App\Models\BI\KPI;
use App\Models\User;
use Illuminate\Database\Seeder;

class BISystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Standard KPIs
        $kpis = [
            ['name' => 'Monthly Revenue', 'code' => 'monthly_revenue', 'category' => 'Finance', 'unit' => 'XAF'],
            ['name' => 'Average Repair Time', 'code' => 'avg_repair_time', 'category' => 'Repair', 'unit' => 'minutes'],
            ['name' => 'Customer Satisfaction', 'code' => 'customer_satisfaction', 'category' => 'Reputation', 'unit' => '%'],
            ['name' => 'Active Vehicles', 'code' => 'active_vehicles', 'category' => 'Fleet', 'unit' => 'units'],
        ];

        foreach ($kpis as $k) {
            KPI::firstOrCreate(['code' => $k['code']], $k);
        }

        // 2. Default Dashboard for Admin
        $admin = User::where('email', 'admin@piiston.com')->first();
        if ($admin) {
            $dashboard = Dashboard::firstOrCreate([
                'owner_type' => 'User',
                'owner_id' => $admin->id,
                'name' => 'Platform Analytics',
            ], [
                'description' => 'Main administration overview dashboard.',
                'is_default' => true,
            ]);

            // Add Widgets
            DashboardWidget::firstOrCreate([
                'dashboard_id' => $dashboard->id,
                'title' => 'Revenue Growth',
            ], [
                'widget_type' => 'CHART',
                'configuration' => ['type' => 'line', 'source' => 'monthly_revenue'],
                'width' => 2,
                'height' => 1,
            ]);

            DashboardWidget::firstOrCreate([
                'dashboard_id' => $dashboard->id,
                'title' => 'Repair Efficiency',
            ], [
                'widget_type' => 'KPI',
                'configuration' => ['source' => 'avg_repair_time'],
                'width' => 1,
                'height' => 1,
            ]);
        }
    }
}
