<?php

namespace App\Providers;

use App\Repositories\Administration\AdminRepositoryInterface;
use App\Repositories\Administration\EloquentAdminRepository;
use App\Repositories\Administration\EloquentSettingsRepository;
use App\Repositories\Administration\SettingsRepositoryInterface;
use App\Repositories\Finance\EloquentInvoiceRepository;
use App\Repositories\Finance\EloquentPaymentRepository;
use App\Repositories\Finance\EloquentSubscriptionRepository;
use App\Repositories\Finance\EloquentWalletRepository;
use App\Repositories\Finance\InvoiceRepositoryInterface;
use App\Repositories\Finance\PaymentRepositoryInterface;
use App\Repositories\Finance\SubscriptionRepositoryInterface;
use App\Repositories\Finance\WalletRepositoryInterface;
use App\Repositories\Fleets\EloquentFleetRepository;
use App\Repositories\Fleets\FleetRepositoryInterface;
use App\Repositories\Garages\EloquentGarageRepository;
use App\Repositories\Garages\GarageRepositoryInterface;
use App\Repositories\Identity\EloquentRoleRepository;
use App\Repositories\Identity\EloquentUserRepository;
use App\Repositories\Identity\RoleRepositoryInterface;
use App\Repositories\Identity\UserRepositoryInterface;
use App\Repositories\Marketplace\EloquentOrderRepository;
use App\Repositories\Marketplace\EloquentProductRepository;
use App\Repositories\Marketplace\EloquentStoreRepository;
use App\Repositories\Marketplace\OrderRepositoryInterface;
use App\Repositories\Marketplace\ProductRepositoryInterface;
use App\Repositories\Marketplace\StoreRepositoryInterface;
use App\Repositories\Mechanics\EloquentMechanicRepository;
use App\Repositories\Mechanics\MechanicRepositoryInterface;
use App\Repositories\Messaging\ConversationRepositoryInterface;
use App\Repositories\Messaging\EloquentConversationRepository;
use App\Repositories\Messaging\EloquentMessageRepository;
use App\Repositories\Messaging\MessageRepositoryInterface;
use App\Repositories\Notifications\DeviceRepositoryInterface;
use App\Repositories\Notifications\EloquentDeviceRepository;
use App\Repositories\Notifications\EloquentNotificationRepository;
use App\Repositories\Notifications\NotificationRepositoryInterface;
use App\Repositories\Vehicles\EloquentVehicleRepository;
use App\Repositories\Vehicles\VehicleRepositoryInterface;
use App\Repositories\Workflows\EloquentRepairRepository;
use App\Repositories\Workflows\RepairRepositoryInterface;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            EloquentUserRepository::class
        );
        $this->app->bind(
            RoleRepositoryInterface::class,
            EloquentRoleRepository::class
        );
        $this->app->bind(
            VehicleRepositoryInterface::class,
            EloquentVehicleRepository::class
        );
        $this->app->bind(
            GarageRepositoryInterface::class,
            EloquentGarageRepository::class
        );
        $this->app->bind(
            MechanicRepositoryInterface::class,
            EloquentMechanicRepository::class
        );
        $this->app->bind(
            RepairRepositoryInterface::class,
            EloquentRepairRepository::class
        );
        $this->app->bind(
            ProductRepositoryInterface::class,
            EloquentProductRepository::class
        );
        $this->app->bind(
            OrderRepositoryInterface::class,
            EloquentOrderRepository::class
        );
        $this->app->bind(
            StoreRepositoryInterface::class,
            EloquentStoreRepository::class
        );
        $this->app->bind(
            FleetRepositoryInterface::class,
            EloquentFleetRepository::class
        );
        $this->app->bind(
            NotificationRepositoryInterface::class,
            EloquentNotificationRepository::class
        );
        $this->app->bind(
            DeviceRepositoryInterface::class,
            EloquentDeviceRepository::class
        );
        $this->app->bind(
            PaymentRepositoryInterface::class,
            EloquentPaymentRepository::class
        );
        $this->app->bind(
            WalletRepositoryInterface::class,
            EloquentWalletRepository::class
        );
        $this->app->bind(
            InvoiceRepositoryInterface::class,
            EloquentInvoiceRepository::class
        );
        $this->app->bind(
            SubscriptionRepositoryInterface::class,
            EloquentSubscriptionRepository::class
        );
        $this->app->bind(
            AdminRepositoryInterface::class,
            EloquentAdminRepository::class
        );
        $this->app->bind(
            SettingsRepositoryInterface::class,
            EloquentSettingsRepository::class
        );
        $this->app->bind(
            ConversationRepositoryInterface::class,
            EloquentConversationRepository::class
        );
        $this->app->bind(
            MessageRepositoryInterface::class,
            EloquentMessageRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
