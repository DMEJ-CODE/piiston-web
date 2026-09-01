<?php

namespace App\Livewire\Admin;

use App\Models\Administration\AdminAuditLog;
use App\Models\Administration\Administrator;
use App\Models\Administration\ModerationCase;
use App\Models\Administration\PlatformReport;
use App\Models\Finance\PlatformRevenue;
use App\Models\Garages\GarageCompany;
use App\Models\Marketplace\Order;
use App\Models\Marketplace\SparePart;
use App\Models\User;
use App\Models\Vehicles\Vehicle;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Dashboard extends Component
{
    #[Layout('layouts.admin')]
    public function render()
    {
        // Platform Overview
        $totalUsers = User::count();
        $totalGarages = GarageCompany::count();
        $totalVehicles = Vehicle::count();
        $totalRevenue = PlatformRevenue::sum('amount');

        // Marketplace Activity
        $totalProducts = SparePart::count();
        $totalOrders = Order::count();
        $recentOrders = Order::with(['buyer', 'items'])->latest()->limit(5)->get();

        // Administrative Tasks
        $activeAdmins = Administrator::where('status', true)->count();
        $pendingReports = PlatformReport::where('status', 'pending')->count();
        $activeModerationCases = ModerationCase::where('status', 'open')->count();

        // Logs
        $recentAuditLogs = AdminAuditLog::with('administrator.user')->latest()->limit(8)->get();

        return view('livewire.admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalGarages' => $totalGarages,
            'totalVehicles' => $totalVehicles,
            'totalRevenue' => $totalRevenue,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'recentOrders' => $recentOrders,
            'activeAdmins' => $activeAdmins,
            'pendingReports' => $pendingReports,
            'activeModerationCases' => $activeModerationCases,
            'recentAuditLogs' => $recentAuditLogs,
        ]);
    }
}
