<?php

namespace App\Http\Controllers\Api\v1\Garage;

use App\Http\Controllers\Controller;
use App\Models\Garages\GarageBranch;
use App\Services\Garages\ReportService;
use App\Services\Garages\SubscriptionService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        protected ReportService $reportService,
        protected SubscriptionService $subscriptionService
    ) {}

    public function branch(GarageBranch $branch): JsonResponse
    {
        $this->authorize('manageBranch', $branch);

        $stats = $this->reportService->getBranchDashboard($branch);
        $subscription = $this->subscriptionService->getActiveSubscription($branch);

        return response()->json([
            'stats' => $stats,
            'subscription' => $subscription,
        ]);
    }

    public function company(GarageBranch $branch): JsonResponse
    {
        $this->authorize('manage', $branch->company);

        $stats = $this->reportService->getCompanyDashboard($branch->company);
        $subscription = $this->subscriptionService->getActiveSubscription($branch);

        return response()->json([
            'stats' => $stats,
            'subscription' => $subscription,
        ]);
    }
}
