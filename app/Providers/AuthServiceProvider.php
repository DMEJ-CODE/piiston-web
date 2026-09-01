<?php

namespace App\Providers;

use App\Models\Fleets\Fleet;
use App\Models\Garages\AuditLog;
use App\Models\Garages\EquipmentMaintenance;
use App\Models\Garages\GarageAppointment;
use App\Models\Garages\GarageBranch;
use App\Models\Garages\GarageCompany;
use App\Models\Garages\GarageCustomer;
use App\Models\Garages\GarageDepartment;
use App\Models\Garages\GarageEmployee;
use App\Models\Garages\GarageEquipment;
use App\Models\Garages\GarageInventory;
use App\Models\Garages\GarageInvoice;
use App\Models\Garages\GaragePayment;
use App\Models\Garages\GaragePurchaseOrder;
use App\Models\Garages\GarageReview;
use App\Models\Garages\GarageService;
use App\Models\Garages\GarageSubscription;
use App\Models\Garages\GarageSupplier;
use App\Models\Garages\PurchaseOrderItem;
use App\Models\Garages\RepairEstimate;
use App\Models\Garages\RepairOrder;
use App\Models\Garages\RepairPart;
use App\Models\Garages\RepairPartUsage;
use App\Models\Garages\RepairTask;
use App\Models\Garages\VehicleCheckIn;
use App\Models\Garages\VehicleDiagnosis;
use App\Models\Garages\WarrantyClaim;
use App\Models\Garages\WorkshopBay;
use App\Models\Marketplace\Order;
use App\Models\Marketplace\SellerProfile;
use App\Models\Mechanics\MechanicProfile;
use App\Models\Notifications\Notification;
use App\Models\Vehicles\Vehicle;
use App\Policies\FleetPolicy;
use App\Policies\GaragePolicy;
use App\Policies\MarketplacePolicy;
use App\Policies\MechanicPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\RepairOrderPolicy;
use App\Policies\VehiclePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        GarageCompany::class => GaragePolicy::class,
        GarageBranch::class => GaragePolicy::class,
        GarageDepartment::class => GaragePolicy::class,
        GarageEmployee::class => GaragePolicy::class,
        GarageCustomer::class => GaragePolicy::class,
        WorkshopBay::class => GaragePolicy::class,
        GarageService::class => GaragePolicy::class,
        GarageSupplier::class => GaragePolicy::class,
        GarageEquipment::class => GaragePolicy::class,
        EquipmentMaintenance::class => GaragePolicy::class,
        VehicleCheckIn::class => GaragePolicy::class,
        GarageAppointment::class => GaragePolicy::class,
        RepairOrder::class => RepairOrderPolicy::class,
        RepairTask::class => GaragePolicy::class,
        RepairEstimate::class => GaragePolicy::class,
        RepairPartUsage::class => GaragePolicy::class,
        RepairPart::class => GaragePolicy::class,
        GarageInventory::class => GaragePolicy::class,
        GaragePurchaseOrder::class => GaragePolicy::class,
        PurchaseOrderItem::class => GaragePolicy::class,
        GarageInvoice::class => GaragePolicy::class,
        GaragePayment::class => GaragePolicy::class,
        GarageReview::class => GaragePolicy::class,
        WarrantyClaim::class => GaragePolicy::class,
        GarageSubscription::class => GaragePolicy::class,
        AuditLog::class => GaragePolicy::class,
        VehicleDiagnosis::class => GaragePolicy::class,
        Vehicle::class => VehiclePolicy::class,
        Notification::class => NotificationPolicy::class,
        Fleet::class => FleetPolicy::class,
        SellerProfile::class => MarketplacePolicy::class,
        Order::class => MarketplacePolicy::class,
        MechanicProfile::class => MechanicPolicy::class,
    ];

    protected function gates(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('ADMIN') ? true : null;
        });
    }

    public function boot(): void
    {
        $this->registerPolicies();
        $this->gates();
    }
}
