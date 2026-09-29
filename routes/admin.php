<?php

use App\Livewire\Admin\AdministratorsManagement;
use App\Livewire\Admin\AdminRoles;
use App\Livewire\Admin\ApplicationErrors;
use App\Livewire\Admin\AuditLogs;
use App\Livewire\Admin\CMSPages;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\FeatureFlags;
use App\Livewire\Admin\FraudCases;
use App\Livewire\Admin\GarageSubscriptions;
use App\Livewire\Admin\ModerationCases;
use App\Livewire\Admin\Permissions;
use App\Livewire\Admin\PlatformReports;
use App\Livewire\Admin\SystemSettings;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Messaging\ChatCenter;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');

    // Administrators Management
    Route::get('/administrators', AdministratorsManagement::class)->name('administrators');

    // Admin Roles
    Route::get('/roles', AdminRoles::class)->name('roles');

    // Permissions
    Route::get('/permissions', Permissions::class)->name('permissions');

    // Users Management
    Route::get('/users', UserManagement::class)->name('users');

    // System Settings
    Route::get('/settings', SystemSettings::class)->name('settings');

    // Audit Logs
    Route::get('/audit-logs', AuditLogs::class)->name('audit-logs');
    Route::get('/application-errors', ApplicationErrors::class)->name('application-errors');

    // CMS Pages
    Route::get('/cms-pages', CMSPages::class)->name('cms-pages');

    // Platform Reports & Moderation
    Route::get('/reports', PlatformReports::class)->name('reports');
    Route::get('/moderation', ModerationCases::class)->name('moderation');

    // Fraud Detection
    Route::get('/fraud-cases', FraudCases::class)->name('fraud-cases');

    // Feature Flags
    Route::get('/feature-flags', FeatureFlags::class)->name('feature-flags');
    Route::get('/garage-subscriptions', GarageSubscriptions::class)->name('garage-subscriptions');
    Route::get('/messages/{conversationId?}', ChatCenter::class)->name('messages.index');
});
