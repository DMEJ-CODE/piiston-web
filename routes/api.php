<?php

use App\Http\Controllers\Api\Admin\AnalyticsController;
use App\Http\Controllers\Api\Admin\CMSController;
use App\Http\Controllers\Api\Admin\SystemController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\VerificationController;
use App\Http\Controllers\Api\AI\AssistantController;
use App\Http\Controllers\Api\AI\DiagnosisController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BI\DashboardController;
use App\Http\Controllers\Api\BI\ReportController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\Documents\ActionController;
use App\Http\Controllers\Api\Documents\DocumentController;
use App\Http\Controllers\Api\GarageCompanyController;
use App\Http\Controllers\Api\GlobalizationController;
use App\Http\Controllers\Api\Integrations\DeveloperController;
use App\Http\Controllers\Api\Integrations\WebhookController;
use App\Http\Controllers\Api\Maps\GeofenceController;
use App\Http\Controllers\Api\Maps\LocationController;
use App\Http\Controllers\Api\Maps\RoutingController;
use App\Http\Controllers\Api\Maps\TrackingController;
use App\Http\Controllers\Api\MarketplaceController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PresenceController;
use App\Http\Controllers\Api\Promotions\CampaignController;
use App\Http\Controllers\Api\Promotions\CouponController;
use App\Http\Controllers\Api\Search\RecommendationController;
use App\Http\Controllers\Api\Search\SearchController;
use App\Http\Controllers\Api\Search\UserActionController;
use App\Http\Controllers\Api\v1\ActivityController;
use App\Http\Controllers\Api\v1\AddressController as v1AddressController;
use App\Http\Controllers\Api\v1\Admin\AIController as v1AdminAIController;
use App\Http\Controllers\Api\v1\Admin\SettingsController as v1AdminSettingsController;
use App\Http\Controllers\Api\v1\Admin\UserController as v1AdminUserController;
use App\Http\Controllers\Api\v1\AppointmentController as v1AppointmentController;
use App\Http\Controllers\Api\v1\AuthController as v1AuthController;
use App\Http\Controllers\Api\v1\EmergencyRequestController as v1EmergencyRequestController;
use App\Http\Controllers\Api\v1\ExpenseController as v1ExpenseController;
use App\Http\Controllers\Api\v1\Finance\InvoiceController as v1InvoiceController;
use App\Http\Controllers\Api\v1\Finance\PaymentController as v1PaymentController;
use App\Http\Controllers\Api\v1\Finance\SubscriptionController as v1SubscriptionController;
use App\Http\Controllers\Api\v1\Finance\WalletController as v1WalletController;
use App\Http\Controllers\Api\v1\Fleet\DriverController as v1FleetDriverController;
use App\Http\Controllers\Api\v1\Fleet\ExpenseController as v1FleetExpenseController;
use App\Http\Controllers\Api\v1\Fleet\FleetController as v1FleetController;
use App\Http\Controllers\Api\v1\Fleet\VehicleController as v1FleetVehicleController;
use App\Http\Controllers\Api\v1\Garage\AppointmentController as v1GarageAppointmentController;
use App\Http\Controllers\Api\v1\Garage\BranchController as v1GarageBranchController;
use App\Http\Controllers\Api\v1\Garage\CustomerController as v1GarageCustomerController;
use App\Http\Controllers\Api\v1\Garage\DashboardController as v1GarageDashboardController;
use App\Http\Controllers\Api\v1\Garage\EmployeeController as v1GarageEmployeeController;
use App\Http\Controllers\Api\v1\Garage\PurchaseOrderController as v1GaragePurchaseOrderController;
use App\Http\Controllers\Api\v1\Garage\RepairPartController as v1GarageRepairPartController;
use App\Http\Controllers\Api\v1\Garage\WarrantyClaimController as v1GarageWarrantyClaimController;
use App\Http\Controllers\Api\v1\GarageController as v1GarageController;
use App\Http\Controllers\Api\v1\IncidentController as v1IncidentController;
use App\Http\Controllers\Api\v1\InsuranceController as v1InsuranceController;
use App\Http\Controllers\Api\v1\MaintenanceAlertController as v1MaintenanceAlertController;
use App\Http\Controllers\Api\v1\Marketplace\CartController as v1CartController;
use App\Http\Controllers\Api\v1\Marketplace\OrderController as v1OrderController;
use App\Http\Controllers\Api\v1\Marketplace\PartQuoteController as v1PartQuoteController;
use App\Http\Controllers\Api\v1\Marketplace\PartRequestController as v1PartRequestController;
use App\Http\Controllers\Api\v1\Marketplace\ProductController as v1ProductController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\AIAssistantController as v1SellerAIController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\AnalyticsController as v1SellerAnalyticsController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\B2BQuoteController as v1SellerB2BQuoteController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\CustomerController as v1SellerCustomerController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\DashboardController as v1SellerDashboardController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\DeliveryController as v1SellerDeliveryController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\FinanceController as v1SellerFinanceController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\OnboardingController as v1SellerOnboardingController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\OrderController as v1SellerOrderController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\ProductController as v1SellerProductController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\PromotionController as v1SellerPromotionController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\PurchaseController as v1SellerPurchaseController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\ReviewController as v1SellerReviewController;
use App\Http\Controllers\Api\v1\Marketplace\Seller\SupplierController as v1SellerSupplierController;
use App\Http\Controllers\Api\v1\Marketplace\StoreController as v1StoreController;
use App\Http\Controllers\Api\v1\MarketplaceIssueController as v1MarketplaceIssueController;
use App\Http\Controllers\Api\v1\MechanicController as v1MechanicController;
use App\Http\Controllers\Api\v1\Messaging\ConversationController as v1ConversationController;
use App\Http\Controllers\Api\v1\Messaging\MessageController as v1MessageController;
use App\Http\Controllers\Api\v1\Notifications\DeviceController as v1DeviceController;
use App\Http\Controllers\Api\v1\Notifications\NotificationController as v1NotificationController;
use App\Http\Controllers\Api\v1\Notifications\PreferenceController as v1PreferenceController;
use App\Http\Controllers\Api\v1\OwnershipHistoryController as v1OwnershipHistoryController;
use App\Http\Controllers\Api\v1\RecommendationController as v1RecommendationController;
use App\Http\Controllers\Api\v1\RepairController as v1RepairController;
use App\Http\Controllers\Api\v1\RepairWorkflowController as v1RepairWorkflowController;
use App\Http\Controllers\Api\v1\ReviewController as v1ReviewController;
use App\Http\Controllers\Api\v1\SearchController as v1SearchController;
use App\Http\Controllers\Api\v1\Social\FeedInteractionController;
use App\Http\Controllers\Api\v1\SOSTrackingController as v1SOSTrackingController;
use App\Http\Controllers\Api\v1\UserController as v1UserController;
use App\Http\Controllers\Api\v1\VehicleCatalogController as v1VehicleCatalogController;
use App\Http\Controllers\Api\v1\VehicleController as v1VehicleController;
use App\Http\Controllers\Api\v1\VehicleDocumentController as v1VehicleDocumentController;
use App\Http\Controllers\Api\v1\VehicleMaintenanceController as v1VehicleMaintenanceController;
use App\Http\Controllers\Api\v1\WarrantyController as v1WarrantyController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\WorkflowController;
use App\Http\Controllers\Api\WorkshopController;
use App\Http\Controllers\SystemAgentController;
use Illuminate\Support\Facades\Route;

// Public Routes (Legacy or Global)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/countries', [GlobalizationController::class, 'countries']);
Route::get('/countries/{iso_code}', [GlobalizationController::class, 'countryDetails']);
Route::get('/countries/{country}/regions', [GlobalizationController::class, 'regions']);
Route::get('/regions/{region}/cities', [GlobalizationController::class, 'cities']);
Route::get('/vehicle-brands', [VehicleController::class, 'brands']);
Route::get('/vehicle-brands/{brand}/models', [VehicleController::class, 'models']);

// API v1 (Enterprise Structure) - PREFIX REMOVED
Route::group([], function () {
    Route::post('/auth/register', [v1AuthController::class, 'register']);
    Route::post('/auth/login', [v1AuthController::class, 'login']);
    Route::post('/auth/check-email', [v1AuthController::class, 'checkEmail']);
    Route::post('/auth/check-phone', [v1AuthController::class, 'checkPhone']);
    Route::post('/auth/send-otp', [v1AuthController::class, 'sendOtp']);
    Route::post('/auth/verify-otp', [v1AuthController::class, 'verifyOtp']);

    // Catalog & Discovery (Public)
    Route::get('/catalog/brands', [v1VehicleCatalogController::class, 'brands']);
    Route::get('/catalog/brands/{brand}/models', [v1VehicleCatalogController::class, 'models']);
    Route::get('/catalog/fuel-types', [v1VehicleCatalogController::class, 'fuelTypes']);
    Route::get('/catalog/transmissions', [v1VehicleCatalogController::class, 'transmissions']);
    Route::get('/garages', [v1GarageController::class, 'index']);
    Route::get('/garages/nearby', [v1GarageController::class, 'nearby']);
    Route::get('/garages/{id}', [v1GarageController::class, 'show']);
    Route::get('/branches/{branch}/services', [v1GarageController::class, 'branchServices']);
    Route::get('/mechanics', [v1MechanicController::class, 'index']);
    Route::get('/mechanics/{id}', [v1MechanicController::class, 'show']);

    // Marketplace Public
    Route::get('/marketplace/products', [v1ProductController::class, 'index']);
    Route::get('/marketplace/products/search', [v1ProductController::class, 'search']);
    Route::get('/marketplace/products/{id}', [v1ProductController::class, 'show']);
    Route::get('/marketplace/stores/{id}', [v1StoreController::class, 'show']);
    Route::get('/marketplace/stores/{id}/products', [v1StoreController::class, 'products']);

    // Public Search
    Route::get('/search/global', [v1SearchController::class, 'globalSearch']);
    Route::get('/search/trending', [v1RecommendationController::class, 'trending']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [v1AuthController::class, 'logout']);
        Route::get('/auth/user', [v1AuthController::class, 'user']);

        Route::get('/user/profile', [v1UserController::class, 'profile']);
        Route::put('/user/profile', [v1UserController::class, 'updateProfile']);
        Route::delete('/user/profile', [v1UserController::class, 'destroy']);
        Route::get('/activities', [ActivityController::class, 'index']);

        // Vehicle Routes
        Route::apiResource('vehicles', v1VehicleController::class);
        Route::get('vehicles/vin/{vin}', [v1VehicleController::class, 'showByVin']);
        Route::put('vehicles/{vehicle}/mileage', [v1VehicleController::class, 'updateMileage']);
        Route::apiResource('vehicles.maintenances', v1VehicleMaintenanceController::class)->shallow();
        Route::apiResource('vehicles.documents', v1VehicleDocumentController::class)->shallow();

        // Appointment Routes
        Route::get('/appointments', [v1AppointmentController::class, 'index']);
        Route::post('/appointments', [v1AppointmentController::class, 'store']);
        Route::get('/appointments/{id}', [v1AppointmentController::class, 'show']);
        Route::post('/appointments/{id}/confirm', [v1AppointmentController::class, 'confirm']);
        Route::post('/appointments/{id}/cancel', [v1AppointmentController::class, 'cancel']);

        // Emergency Assistance
        Route::get('/emergency-requests', [v1EmergencyRequestController::class, 'index']);
        Route::get('/emergency-requests/active', [v1EmergencyRequestController::class, 'active']);
        Route::post('/emergency-requests', [v1EmergencyRequestController::class, 'store']);
        Route::get('/emergency-requests/{id}', [v1EmergencyRequestController::class, 'show']);
        Route::post('/emergency-requests/{id}/cancel', [v1EmergencyRequestController::class, 'cancel']);
        Route::post('/emergency-requests/{id}/accept', [v1EmergencyRequestController::class, 'accept']);
        Route::post('/emergency-requests/{id}/arrive', [v1EmergencyRequestController::class, 'arrive']);
        Route::post('/emergency-requests/{id}/service-sheet', [v1EmergencyRequestController::class, 'submitServiceSheet']);
        Route::post('/emergency-requests/{id}/approve-estimate', [v1EmergencyRequestController::class, 'approveEstimate']);
        Route::post('/emergency-requests/{id}/complete', [v1EmergencyRequestController::class, 'complete']);
        Route::post('/emergency-requests/{id}/decline', [v1EmergencyRequestController::class, 'decline']);
        Route::get('/emergency-requests/{id}/tracking', [v1SOSTrackingController::class, 'show']);
        Route::post('/emergency-requests/{id}/tracking', [v1SOSTrackingController::class, 'store']);

        // Expenses & Reviews
        Route::get('/expenses/summary', [v1ExpenseController::class, 'index']);
        Route::post('/reviews', [v1ReviewController::class, 'store']);
        Route::get('/reviews/my-reviews', [v1ReviewController::class, 'index']);

        // User Addresses
        Route::get('/user/addresses', [v1AddressController::class, 'index']);
        Route::post('/user/addresses', [v1AddressController::class, 'store']);
        Route::delete('/user/addresses/{id}', [v1AddressController::class, 'destroy']);

        // Vehicle Incidents/Accidents
        Route::get('/incidents', [v1IncidentController::class, 'index']);
        Route::post('/incidents', [v1IncidentController::class, 'store']);
        Route::get('/vehicles/{vehicle}/ownership-history', [v1OwnershipHistoryController::class, 'index']);

        // Maintenance Alerts, Warranties & Insurances
        Route::get('/maintenance-alerts', [v1MaintenanceAlertController::class, 'index']);
        Route::put('/maintenance-alerts/{id}/status', [v1MaintenanceAlertController::class, 'updateStatus']);
        Route::get('/warranties', [v1WarrantyController::class, 'index']);
        Route::get('/insurances', [v1InsuranceController::class, 'index']);
        Route::post('/insurances', [v1InsuranceController::class, 'store']);

        // Garage Management
        Route::post('/garages', [v1GarageController::class, 'store']);

        Route::prefix('garage')->group(function () {
            Route::get('/dashboard/branch/{branch}', [v1GarageDashboardController::class, 'branch']);
            Route::get('/dashboard/company/{branch}', [v1GarageDashboardController::class, 'company']);

            Route::prefix('companies/{company}')->group(function () {
                Route::get('/branches', [v1GarageBranchController::class, 'index']);
                Route::post('/branches', [v1GarageBranchController::class, 'store']);
                Route::get('/branches/{branch}', [v1GarageBranchController::class, 'show']);
                Route::get('/branches/{branch}/employees', [v1GarageBranchController::class, 'employees']);
                Route::get('/branches/{branch}/customers', [v1GarageBranchController::class, 'customers']);
            });

            Route::prefix('branches/{branch}')->group(function () {
                Route::get('/parts/low-stock', [v1GarageRepairPartController::class, 'lowStock']);
                Route::apiResource('parts', v1GarageRepairPartController::class)->shallow();
                Route::apiResource('employees', v1GarageEmployeeController::class)->shallow();
                Route::apiResource('customers', v1GarageCustomerController::class)->shallow();
                Route::apiResource('appointments', v1GarageAppointmentController::class)->shallow();
                Route::post('/appointments/{appointment}/confirm', [v1GarageAppointmentController::class, 'confirm']);
                Route::post('/appointments/{appointment}/repropose', [v1GarageAppointmentController::class, 'repropose']);
                Route::get('/appointments/{appointment}/slots', [v1GarageAppointmentController::class, 'slots']);
                Route::apiResource('purchase-orders', v1GaragePurchaseOrderController::class)->shallow();
                Route::post('/purchase-orders/{purchaseOrder}/receive', [v1GaragePurchaseOrderController::class, 'receive']);
                Route::apiResource('warranty-claims', v1GarageWarrantyClaimController::class)->shallow();
            });
        });

        // Mechanic Management
        Route::get('/mechanic/profile', [v1MechanicController::class, 'myProfile']);
        Route::get('/mechanic/assignments', [v1MechanicController::class, 'assignments']);
        Route::post('/mechanics', [v1MechanicController::class, 'store']);

        // Repair & Workflow
        Route::apiResource('repairs', v1RepairController::class);
        Route::get('repairs/{repair}/history', [v1RepairController::class, 'history']);
        Route::post('repairs/{repair}/diagnostic', [v1RepairWorkflowController::class, 'submitDiagnosis']);
        Route::post('repairs/{repair}/estimate', [v1RepairWorkflowController::class, 'submitEstimate']);
        Route::post('repairs/{repair}/approve', [v1RepairWorkflowController::class, 'approve']);
        Route::post('repairs/{repair}/tasks/{task}/approve', [v1RepairWorkflowController::class, 'approveTask']);
        Route::post('repairs/{repair}/tasks/{task}/reject', [v1RepairWorkflowController::class, 'rejectTask']);

        // Marketplace Protected
        Route::apiResource('marketplace/part-requests', v1PartRequestController::class);
        Route::post('marketplace/part-requests/{id}/close', [v1PartRequestController::class, 'close']);
        Route::post('marketplace/quotes/{id}/accept', [v1PartQuoteController::class, 'accept']);

        Route::get('/marketplace/cart', [v1CartController::class, 'index']);
        Route::post('/marketplace/cart/add', [v1CartController::class, 'add']);
        Route::delete('/marketplace/cart/remove/{itemId}', [v1CartController::class, 'remove']);
        Route::get('/marketplace/orders', [v1OrderController::class, 'index']);
        Route::get('/marketplace/orders/{id}', [v1OrderController::class, 'show']);
        Route::post('/marketplace/orders', [v1OrderController::class, 'store']);
        // Marketplace protected routes...
        Route::post('/marketplace/orders/report-issue', [v1MarketplaceIssueController::class, 'store']);

        // Seller Specific Routes
        Route::prefix('seller')->group(function () {
            Route::get('/dashboard', [v1SellerDashboardController::class, 'index']);
            Route::get('/products', [v1SellerProductController::class, 'index']);
            Route::post('/products', [v1SellerProductController::class, 'store']);
            Route::put('/products/{id}', [v1SellerProductController::class, 'update']);
            Route::delete('/products/{id}', [v1SellerProductController::class, 'destroy']);
            Route::get('/orders', [v1SellerOrderController::class, 'index']);
            Route::get('/orders/{id}', [v1SellerOrderController::class, 'show']);
            Route::put('/orders/{id}/status', [v1SellerOrderController::class, 'updateStatus']);

            // Professional Extensions
            Route::apiResource('suppliers', v1SellerSupplierController::class);
            Route::get('/purchases', [v1SellerPurchaseController::class, 'index']);
            Route::post('/purchases', [v1SellerPurchaseController::class, 'store']);
            Route::post('/purchases/{id}/receive', [v1SellerPurchaseController::class, 'receive']);

            Route::get('/b2b/requests', [v1SellerB2BQuoteController::class, 'requests']);
            Route::get('/b2b/quotes', [v1SellerB2BQuoteController::class, 'myQuotes']);
            Route::post('/b2b/requests/{requestId}/quote', [v1SellerB2BQuoteController::class, 'store']);

            Route::get('/analytics/sales', [v1SellerAnalyticsController::class, 'salesSummary']);
            Route::get('/analytics/stock-health', [v1SellerAnalyticsController::class, 'stockHealth']);
            Route::get('/deliveries', [v1SellerDeliveryController::class, 'index']);
            Route::put('/deliveries/{id}', [v1SellerDeliveryController::class, 'update']);

            // Final Professional Modules
            Route::get('/finance/summary', [v1SellerFinanceController::class, 'walletSummary']);
            Route::get('/finance/transactions', [v1SellerFinanceController::class, 'transactions']);
            Route::put('/finance/payout-info', [v1SellerFinanceController::class, 'updatePayoutInfo']);

            Route::apiResource('promotions', v1SellerPromotionController::class)->only(['index', 'store', 'destroy']);
            Route::get('/reviews', [v1SellerReviewController::class, 'index']);
            Route::post('/reviews/{id}/respond', [v1SellerReviewController::class, 'respond']);

            Route::post('/ai/identify', [v1SellerAIController::class, 'identifyPart']);
            Route::post('/ai/generate-description', [v1SellerAIController::class, 'generateDescription']);

            Route::post('/onboarding/complete', [v1SellerOnboardingController::class, 'complete']);
            Route::get('/customers', [v1SellerCustomerController::class, 'index']);
        });

        // Fleet Management
        Route::apiResource('fleets', v1FleetController::class);
        Route::get('fleets/{fleet}/vehicles', [v1FleetVehicleController::class, 'index']);
        Route::post('fleets/{fleet}/vehicles', [v1FleetVehicleController::class, 'store']);
        Route::get('fleets/{fleet}/drivers', [v1FleetDriverController::class, 'index']);
        Route::post('fleets/{fleet}/drivers', [v1FleetDriverController::class, 'store']);
        Route::get('fleets/{fleet}/expenses', [v1FleetExpenseController::class, 'index']);
        Route::post('fleets/{fleet}/expenses', [v1FleetExpenseController::class, 'store']);

        // Notifications
        Route::get('/notifications', [v1NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [v1NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [v1NotificationController::class, 'markAllAsRead']);
        Route::delete('/notifications/{id}', [v1NotificationController::class, 'destroy']);
        Route::get('/notification-preferences', [v1PreferenceController::class, 'index']);
        Route::put('/notification-preferences', [v1PreferenceController::class, 'update']);
        Route::post('/notification-devices/register', [v1DeviceController::class, 'register']);
        Route::post('/notification-devices/unregister', [v1DeviceController::class, 'unregister']);

        // Finance
        Route::get('/payments', [v1PaymentController::class, 'index']);
        Route::post('/payments/pay', [v1PaymentController::class, 'pay']);
        Route::get('/payments/verify/{reference}', [v1PaymentController::class, 'verify']);
        Route::get('/wallet', [v1WalletController::class, 'show']);
        Route::get('/wallet/history', [v1WalletController::class, 'history']);
        Route::post('/wallet/withdraw', [v1WalletController::class, 'withdraw']);
        Route::get('/subscriptions/plans', [v1SubscriptionController::class, 'plans']);
        Route::get('/subscriptions/current', [v1SubscriptionController::class, 'current']);
        Route::post('/subscriptions/subscribe', [v1SubscriptionController::class, 'subscribe']);
        Route::get('/invoices', [v1InvoiceController::class, 'index']);
        Route::get('/invoices/{id}', [v1InvoiceController::class, 'show']);

        // Messaging
        Route::get('/conversations', [v1ConversationController::class, 'index']);
        Route::post('/conversations', [v1ConversationController::class, 'store']);
        Route::get('/conversations/{conversation}', [v1ConversationController::class, 'show']);
        Route::get('/conversations/{conversation}/messages', [v1MessageController::class, 'index']);
        Route::post('/conversations/{conversation}/messages', [v1MessageController::class, 'store']);
        Route::post('/conversations/{conversation}/read', [v1MessageController::class, 'markAsRead']);
        Route::post('/conversations/{conversation}/call', [v1ConversationController::class, 'initiateCall']);

        // Promotions
        Route::prefix('promotions')->group(function () {
            Route::get('/campaigns', [CampaignController::class, 'index']);
            Route::post('/campaigns', [CampaignController::class, 'store']);
            Route::post('/coupons/validate', [CouponController::class, 'validateCoupon']);
        });

        // Integrations
        Route::prefix('integrations')->group(function () {
            Route::get('/applications', [DeveloperController::class, 'myApplications']);
            Route::post('/applications', [DeveloperController::class, 'createApplication']);
            // Un-refactored controller usage here, but following the pattern
            Route::get('/webhooks', [WebhookController::class, 'index']);
            Route::post('/webhooks', [WebhookController::class, 'store']);
        });

        // Search Protected
        Route::get('/search/recommendations', [v1RecommendationController::class, 'index']);

        // Social Interactions
        Route::prefix('social')->group(function () {
            Route::post('/like', [FeedInteractionController::class, 'toggleLike']);
            Route::post('/comment', [FeedInteractionController::class, 'comment']);
            Route::get('/comments', [FeedInteractionController::class, 'getComments']);
        });

        // Administration (v1)
        Route::prefix('admin')->middleware('admin')->group(function () {
            Route::get('/users', [v1AdminUserController::class, 'index']);
            Route::get('/users/{user}', [v1AdminUserController::class, 'show']);
            Route::put('/users/{user}/status', [v1AdminUserController::class, 'updateStatus']);

            Route::get('/settings', [v1AdminSettingsController::class, 'index']);
            Route::post('/settings', [v1AdminSettingsController::class, 'update']);

            // Admin AI Control
            Route::get('/ai/providers', [v1AdminAIController::class, 'providers']);
            Route::get('/ai/usage', [v1AdminAIController::class, 'usage']);
        });

        // Admin only route example
        Route::middleware('role:ADMIN')->get('/admin/stats', function () {
            return response()->json(['message' => 'Admin stats']);
        });
    });
});

// Other legacy/global middleware blocks
Route::middleware('auth:sanctum')->group(function () {
    // GMS Routes
    Route::prefix('gms')->group(function () {
        Route::get('/companies', [GarageCompanyController::class, 'index']);
        Route::get('/companies/{company}', [GarageCompanyController::class, 'show']);
        Route::get('/branches/{branch}/repair-orders', [WorkshopController::class, 'branchRepairOrders']);
        Route::get('/repair-orders/{repairOrder}', [WorkshopController::class, 'repairOrderDetails']);
    });

    // System Agent endpoints (protected)
    Route::prefix('system-agent')->group(function () {
        Route::post('/run', [SystemAgentController::class, 'run']);
        Route::post('/reminders', [SystemAgentController::class, 'store']);
        Route::get('/reminders', [SystemAgentController::class, 'index']);
    });

    // Workflow Routes
    Route::prefix('workflow')->group(function () {
        Route::get('/service-requests', [WorkflowController::class, 'myServiceRequests']);
        Route::post('/service-requests', [WorkflowController::class, 'storeServiceRequest']);
        Route::get('/repair-orders/{repairOrder}/progress', [WorkflowController::class, 'repairProgress']);
        Route::get('/repair-orders/{repairOrder}/diagnosis', [WorkflowController::class, 'diagnosis']);
    });

    // Messaging Routes
    Route::prefix('messaging')->group(function () {
        Route::get('/conversations', [ConversationController::class, 'index']);
        Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);
        Route::get('/conversations/{conversation}/messages', [ConversationController::class, 'getMessages']);
        Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);
        Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead']);
        Route::post('/messages/{message}/react', [MessageController::class, 'react']);
        Route::post('/presence/status', [PresenceController::class, 'updateStatus']);
    });

    // AI Routes
    Route::prefix('ai')->group(function () {
        Route::get('/assistants', [AssistantController::class, 'assistants']);
        Route::get('/conversations', [AssistantController::class, 'conversations']);
        Route::post('/conversations', [AssistantController::class, 'store']);
        Route::post('/conversations/{conversation}/chat', [AssistantController::class, 'chat']);
        Route::get('/diagnoses', [DiagnosisController::class, 'index']);
        Route::post('/diagnoses', [DiagnosisController::class, 'diagnose']);
    });

    // Search Routes
    Route::prefix('search')->group(function () {
        Route::get('/', [SearchController::class, 'globalSearch']);
        Route::get('/personalized', [RecommendationController::class, 'personalized']);
        Route::get('/favorites', [UserActionController::class, 'favorites']);
        Route::post('/favorites/toggle', [UserActionController::class, 'toggleFavorite']);
        Route::get('/history', [UserActionController::class, 'history']);
    });

    // Map & Geolocation Routes
    Route::prefix('maps')->group(function () {
        Route::get('/nearby', [LocationController::class, 'nearby']);
        Route::post('/current-location', [LocationController::class, 'updateCurrent']);
        Route::get('/geo-points', [LocationController::class, 'geoPoints']);
        Route::post('/route/calculate', [RoutingController::class, 'calculate']);
        Route::get('/tracking/sessions', [TrackingController::class, 'sessions']);
        Route::post('/tracking/sessions', [TrackingController::class, 'start']);
        Route::post('/tracking/sessions/{session}/live', [TrackingController::class, 'updateLiveLocation']);
        Route::get('/geofences', [GeofenceController::class, 'index']);
        Route::post('/geofences/{geofence}/event', [GeofenceController::class, 'logEvent']);
    });

    // Administration Routes (Internal)
    Route::prefix('internal-admin')->middleware('auth:sanctum')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::post('/users/{user}/suspend', [AdminUserController::class, 'suspend']);
        Route::post('/users/{user}/reactivate', [AdminUserController::class, 'reactivate']);
        Route::get('/verifications/pending', [VerificationController::class, 'pendingBusinesses']);
        Route::post('/verifications/business/{id}/approve', [VerificationController::class, 'approveBusiness']);
        Route::get('/settings', [SystemController::class, 'settings']);
        Route::post('/settings/{key}', [SystemController::class, 'updateSetting']);
        Route::get('/feature-flags', [SystemController::class, 'featureFlags']);
        Route::post('/feature-flags/{id}/toggle', [SystemController::class, 'toggleFeature']);
        Route::get('/cms/pages', [CMSController::class, 'pages']);
        Route::post('/cms/pages', [CMSController::class, 'storePage']);
        Route::get('/cms/faq', [CMSController::class, 'faq']);
        Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard']);
    });

    // BI Routes
    Route::prefix('bi')->group(function () {
        Route::get('/dashboards', [DashboardController::class, 'index']);
        Route::get('/dashboards/{dashboard}', [DashboardController::class, 'show']);
        Route::get('/reports', [ReportController::class, 'index']);
        Route::post('/reports/generate', [ReportController::class, 'generate']);
    });

    // DMS Routes
    Route::prefix('dms')->group(function () {
        Route::get('/documents', [DocumentController::class, 'index']);
        Route::get('/categories', [DocumentController::class, 'categories']);
        Route::get('/documents/{document}', [DocumentController::class, 'show']);
        Route::get('/documents/{document}/download', [DocumentController::class, 'download']);
        Route::post('/documents/{document}/approve', [ActionController::class, 'approve']);
        Route::post('/documents/{document}/share', [ActionController::class, 'share']);
    });
});

// Public Marketplace
Route::get('/marketplace/parts', [MarketplaceController::class, 'index']);
Route::get('/marketplace/categories', [MarketplaceController::class, 'categories']);
Route::get('/marketplace/parts/{id}', [MarketplaceController::class, 'show']);
