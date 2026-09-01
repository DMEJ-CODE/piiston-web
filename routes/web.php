<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\PublicEstimateController;
use App\Http\Controllers\Web\BranchController;
use App\Http\Controllers\Web\CheckInController;
use App\Http\Controllers\Web\DeliveryController;
use App\Http\Controllers\Web\DiagnosisController;
use App\Http\Controllers\Web\EstimateController;
use App\Http\Controllers\Web\GarageController;
use App\Http\Controllers\Web\GarageEmergencyController;
use App\Http\Controllers\Web\GarageEmployeeController;
use App\Http\Controllers\Web\GarageInvoiceController;
use App\Http\Controllers\Web\GaragePaymentController;
use App\Http\Controllers\Web\GaragePurchaseOrderController;
use App\Http\Controllers\Web\GarageServiceController;
use App\Http\Controllers\Web\GarageSettingsController;
use App\Http\Controllers\Web\GarageWarrantyClaimController;
use App\Http\Controllers\Web\RepairController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\WorkshopBayController;
use App\Http\Middleware\ResolveGarageBranch;
use App\Livewire\Auth\Onboarding;
use App\Livewire\Garage\Setup;
use App\Models\Garages\GarageBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/cookie-policy', [LandingController::class, 'cookiePolicy'])->name('cookie.policy');
Route::post('/cookie-consent', [LandingController::class, 'setConsent'])->name('cookie.consent');

Route::post('/contact', [LandingController::class, 'submitContact'])->name('contact.submit');
Route::post('/newsletter/subscribe', [LandingController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

// Public Estimates Access
Route::get('/estimate/{token}', [PublicEstimateController::class, 'show'])->name('public.estimate.show');
Route::post('/estimate/{token}/approve', [PublicEstimateController::class, 'approve'])->name('public.estimate.approve');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/onboarding', Onboarding::class)->name('onboarding');

    Route::middleware(['onboarding'])->group(function () {
        Route::get('dashboard', function () {
            $user = auth()->user();

            if ($user->administrator()->exists()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->hasRole('GARAGE_OWNER') || $user->hasRole('MECHANIC')) {
                return redirect()->route('garage.dashboard');
            }

            return view('dashboard');
        })->name('dashboard');

        Route::middleware(['auth'])->prefix('garage')->name('garage.')->middleware(ResolveGarageBranch::class)->group(function () {
            Route::get('/setup', Setup::class)->name('setup');
            Route::post('/switch-branch/{branch}', function ($branchId) {
                if ($branchId == 0) {
                    session()->forget('active_garage_branch_id');

                    return redirect()->route('garage.dashboard', ['mode' => 'general'])->with('success', 'Retour à la gestion générale.');
                }

                $branch = GarageBranch::findOrFail($branchId);
                $user = auth()->user();
                if ($user->hasRole('ADMIN') ||
                    $user->id === $branch->company->owner_id ||
                    $user->id === $branch->manager_id ||
                    $branch->employees()->where('user_id', $user->id)->exists()) {
                    session(['active_garage_branch_id' => $branch->id]);

                    return back()->with('success', 'Branche changée : '.$branch->name);
                }

                return back()->with('error', 'Accès non autorisé.');
            })->name('switch-branch');

            Route::get('/dashboard', [GarageController::class, 'dashboard'])->name('dashboard');

            Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
            Route::get('/branches/create', [BranchController::class, 'create'])->name('branches.create');
            Route::post('/branches', [BranchController::class, 'store'])->name('branches.store');
            Route::get('/branches/{targetBranch}/edit', [BranchController::class, 'edit'])->name('branches.edit');
            Route::put('/branches/{targetBranch}', [BranchController::class, 'update'])->name('branches.update');
            Route::get('/reports', [GarageController::class, 'reports'])->name('reports.index');
            Route::get('/reports/advanced', [GarageController::class, 'advancedReports'])->name('reports.advanced');
            Route::get('/settings', [GarageSettingsController::class, 'index'])->name('settings.index');
            Route::patch('/settings', [GarageSettingsController::class, 'update'])->name('settings.update');
            Route::get('/search', [SearchController::class, 'global'])->name('search');
            Route::get('/notifications', function () {
                return view('garage.notifications.index', [
                    'notifications' => auth()->user()->appNotifications ?? [],
                ]);
            })->name('notifications.index');
            Route::get('/support', function () {
                return view('garage.support.index');
            })->name('support');

            Route::get('/emergencies', [GarageEmergencyController::class, 'index'])->name('emergencies.index');
            Route::post('/emergencies/{emergency}/accept', [GarageEmergencyController::class, 'accept'])->name('emergencies.accept');

            Route::get('/check-ins', [CheckInController::class, 'index'])->name('check-ins.index');
            Route::get('/check-ins/create', [CheckInController::class, 'create'])->name('check-ins.create');
            Route::post('/check-ins', [CheckInController::class, 'store'])->name('check-ins.store');
            Route::get('/check-ins/{checkIn}/edit', [CheckInController::class, 'edit'])->name('check-ins.edit');
            Route::put('/check-ins/{checkIn}', [CheckInController::class, 'update'])->name('check-ins.update');
            Route::delete('/check-ins/{checkIn}', [CheckInController::class, 'destroy'])->name('check-ins.destroy');

            Route::get('/diagnoses', [DiagnosisController::class, 'index'])->name('diagnoses.index');
            Route::get('/diagnoses/create', [DiagnosisController::class, 'create'])->name('diagnoses.create');
            Route::post('/diagnoses', [DiagnosisController::class, 'store'])->name('diagnoses.store');
            Route::post('/diagnoses/ai/{repair}', [DiagnosisController::class, 'runAI'])->name('diagnoses.ai');
            Route::get('/diagnoses/{diagnosis}/edit', [DiagnosisController::class, 'edit'])->name('diagnoses.edit');
            Route::put('/diagnoses/{diagnosis}', [DiagnosisController::class, 'update'])->name('diagnoses.update');
            Route::delete('/diagnoses/{diagnosis}', [DiagnosisController::class, 'destroy'])->name('diagnoses.destroy');

            Route::get('/estimates', [EstimateController::class, 'index'])->name('estimates.index');
            Route::get('/estimates/create', [EstimateController::class, 'create'])->name('estimates.create');
            Route::post('/estimates', [EstimateController::class, 'store'])->name('estimates.store');
            Route::get('/estimates/{estimate}/edit', [EstimateController::class, 'edit'])->name('estimates.edit');
            Route::put('/estimates/{estimate}', [EstimateController::class, 'update'])->name('estimates.update');
            Route::delete('/estimates/{estimate}', [EstimateController::class, 'destroy'])->name('estimates.destroy');
            Route::patch('/estimates/{estimate}/approve', [EstimateController::class, 'approve'])->name('estimates.approve');
            Route::patch('/estimates/{estimate}/reject', [EstimateController::class, 'reject'])->name('estimates.reject');

            Route::get('/customers', [GarageController::class, 'customers'])->name('customers.index');
            Route::get('/customers/create', [GarageController::class, 'createCustomer'])->name('customers.create');
            Route::post('/customers', [GarageController::class, 'storeCustomer'])->name('customers.store');
            Route::get('/customers/{customer}/edit', [GarageController::class, 'editCustomer'])->name('customers.edit');
            Route::put('/customers/{customer}', [GarageController::class, 'updateCustomer'])->name('customers.update');
            Route::delete('/customers/{customer}', [GarageController::class, 'destroyCustomer'])->name('customers.destroy');

            Route::get('/appointments', [GarageController::class, 'appointments'])->name('appointments.index');
            Route::get('/appointments/calendar', function () {
                return view('garage.appointments.calendar', ['branch' => request()->attributes->get('garageBranch')]);
            })->name('appointments.calendar');
            Route::get('/appointments/create', [GarageController::class, 'createAppointment'])->name('appointments.create');
            Route::post('/appointments', [GarageController::class, 'storeAppointment'])->name('appointments.store');
            Route::get('/appointments/{appointment}/edit', [GarageController::class, 'editAppointment'])->name('appointments.edit');
            Route::put('/appointments/{appointment}', [GarageController::class, 'updateAppointment'])->name('appointments.update');
            Route::delete('/appointments/{appointment}', [GarageController::class, 'destroyAppointment'])->name('appointments.destroy');

            Route::get('/repairs', [RepairController::class, 'index'])->name('repairs.index');
            Route::get('/repairs/create', [RepairController::class, 'create'])->name('repairs.create');
            Route::post('/repairs', [RepairController::class, 'store'])->name('repairs.store');
            Route::get('/repairs/{repair}', [RepairController::class, 'show'])->name('repairs.show');
            Route::patch('/repairs/{repair}/status', [RepairController::class, 'updateStatus'])->name('repairs.update-status');
            Route::patch('/repairs/{repair}/assign-mechanic', [RepairController::class, 'assignMechanic'])->name('repairs.assign-mechanic');
            Route::patch('/repairs/{repair}/assign-bay', [RepairController::class, 'assignBay'])->name('repairs.assign-bay');
            Route::post('/repairs/{repair}/tasks', [RepairController::class, 'addTask'])->name('repairs.tasks.store');
            Route::post('/repairs/{repair}/parts', [RepairController::class, 'addPart'])->name('repairs.parts.store');
            Route::post('/repairs/{repair}/invoice', [RepairController::class, 'generateInvoice'])->name('repairs.invoice.store');
            Route::get('/repairs/{repair}/invoice/print', [RepairController::class, 'printInvoice'])->name('repairs.invoice.print');
            Route::get('/repairs/{repair}/delivery', [DeliveryController::class, 'create'])->name('repairs.delivery.create');
            Route::post('/repairs/{repair}/delivery', [DeliveryController::class, 'store'])->name('repairs.delivery.store');
            Route::post('/estimates/{estimate}/share', [PublicEstimateController::class, 'generateToken'])->name('estimates.share');

            Route::get('/inventory', [GarageController::class, 'inventory'])->name('inventory.index');
            Route::get('/inventory/create', [GarageController::class, 'createInventory'])->name('inventory.create');
            Route::post('/inventory', [GarageController::class, 'storeInventory'])->name('inventory.store');
            Route::get('/inventory/{part}/edit', [GarageController::class, 'editInventory'])->name('inventory.edit');
            Route::put('/inventory/{part}', [GarageController::class, 'updateInventory'])->name('inventory.update');
            Route::delete('/inventory/{part}', [GarageController::class, 'destroyInventory'])->name('inventory.destroy');

            Route::get('/employees', [GarageEmployeeController::class, 'index'])->name('employees.index');
            Route::get('/employees/create', [GarageEmployeeController::class, 'create'])->name('employees.create');
            Route::post('/employees', [GarageEmployeeController::class, 'store'])->name('employees.store');
            Route::get('/employees/{employee}/edit', [GarageEmployeeController::class, 'edit'])->name('employees.edit');
            Route::put('/employees/{employee}', [GarageEmployeeController::class, 'update'])->name('employees.update');
            Route::delete('/employees/{employee}', [GarageEmployeeController::class, 'destroy'])->name('employees.destroy');

            Route::get('/services', [GarageServiceController::class, 'index'])->name('services.index');
            Route::post('/services', [GarageServiceController::class, 'store'])->name('services.store');

            Route::get('/bays', [WorkshopBayController::class, 'index'])->name('bays.index');
            Route::post('/bays', [WorkshopBayController::class, 'store'])->name('bays.store');

            Route::get('/purchase-orders', [GaragePurchaseOrderController::class, 'index'])->name('purchase-orders.index');
            Route::get('/purchase-orders/create', [GaragePurchaseOrderController::class, 'create'])->name('purchase-orders.create');
            Route::post('/purchase-orders', [GaragePurchaseOrderController::class, 'store'])->name('purchase-orders.store');
            Route::post('/purchase-orders/{purchaseOrder}/receive', [GaragePurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
            Route::get('/purchase-orders/{purchaseOrder}/edit', [GaragePurchaseOrderController::class, 'edit'])->name('purchase-orders.edit');
            Route::put('/purchase-orders/{purchaseOrder}', [GaragePurchaseOrderController::class, 'update'])->name('purchase-orders.update');
            Route::delete('/purchase-orders/{purchaseOrder}', [GaragePurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');

            Route::get('/warranty-claims', [GarageWarrantyClaimController::class, 'index'])->name('warranty-claims.index');
            Route::get('/warranty-claims/create', [GarageWarrantyClaimController::class, 'create'])->name('warranty-claims.create');
            Route::post('/warranty-claims', [GarageWarrantyClaimController::class, 'store'])->name('warranty-claims.store');
            Route::get('/warranty-claims/{warrantyClaim}/edit', [GarageWarrantyClaimController::class, 'edit'])->name('warranty-claims.edit');
            Route::put('/warranty-claims/{warrantyClaim}', [GarageWarrantyClaimController::class, 'update'])->name('warranty-claims.update');
            Route::delete('/warranty-claims/{warrantyClaim}', [GarageWarrantyClaimController::class, 'destroy'])->name('warranty-claims.destroy');

            Route::get('/invoices', [GarageInvoiceController::class, 'index'])->name('invoices.index');
            Route::get('/payments', [GaragePaymentController::class, 'index'])->name('payments.index');
        });
    });
});

Route::get('/welcomeyou', function () {
    return view('welcomeyou');
});
Route::get('/form', function () {
    return view('form');
});
Route::post('/result', function (Request $request) {
    $name = $request->name;

    return view('result', compact('name'));
});
Route::get('/formcreate', function () {
    return view('formcreate');
});
Route::post('/formshow', function (Request $request) {
    return view('formshow', [
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'message' => $request->input('message'),
    ]);
});
// Admin System Agent UI (Reminders)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/system/reminders', function () {
        return view('admin.system.reminders');
    })->name('admin.system.reminders');
});

require __DIR__.'/settings.php';
require __DIR__.'/admin.php';
