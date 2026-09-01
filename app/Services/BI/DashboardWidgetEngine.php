<?php

namespace App\Services\BI;

use App\Models\User;

class DashboardWidgetEngine
{
    /**
     * Get dynamic widgets based on user role and permissions
     */
    public function getRoleWidgets(User $user): array
    {
        if ($user->hasRole('ADMIN')) {
            return $this->getAdminWidgets();
        }

        if ($user->hasRole('GARAGE_OWNER')) {
            return $this->getGarageWidgets();
        }

        return $this->getDefaultWidgets();
    }

    protected function getAdminWidgets(): array
    {
        return [
            ['type' => 'CHART', 'title' => 'Global Revenue', 'config' => ['source' => 'monthly_revenue', 'chart' => 'line']],
            ['type' => 'KPI', 'title' => 'New Users Today', 'config' => ['source' => 'user_registrations_daily']],
        ];
    }

    protected function getGarageWidgets(): array
    {
        return [
            ['type' => 'CHART', 'title' => 'Workshop Efficiency', 'config' => ['source' => 'avg_repair_time', 'chart' => 'bar']],
            ['type' => 'TABLE', 'title' => 'Active Repairs', 'config' => ['source' => 'active_repair_orders']],
        ];
    }

    protected function getDefaultWidgets(): array
    {
        return [
            ['type' => 'KPI', 'title' => 'My Expenses', 'config' => ['source' => 'user_maintenance_costs']],
        ];
    }
}
