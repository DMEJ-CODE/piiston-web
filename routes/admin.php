<?php

use App\Livewire\Admin\AdministratorsManagement;
use App\Livewire\Admin\AdminRoles;
use App\Livewire\Admin\AuditLogs;
use App\Livewire\Admin\CMSPages;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\FeatureFlags;
use App\Livewire\Admin\FraudCases;
use App\Livewire\Admin\ModerationCases;
use App\Livewire\Admin\Permissions;
use App\Livewire\Admin\PlatformReports;
use App\Livewire\Admin\SystemSettings;
use App\Livewire\Admin\UserManagement;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::livewire('/', Dashboard::class)->name('dashboard');

    // Administrators Management
    Route::livewire('/administrators', AdministratorsManagement::class)->name('administrators');

    // Admin Roles
    Route::livewire('/roles', AdminRoles::class)->name('roles');

    // Permissions
    Route::livewire('/permissions', Permissions::class)->name('permissions');

    // Users Management
    Route::livewire('/users', UserManagement::class)->name('users');

    // System Settings
    Route::livewire('/settings', SystemSettings::class)->name('settings');

    // Audit Logs
    Route::livewire('/audit-logs', AuditLogs::class)->name('audit-logs');

    // CMS Pages
    Route::livewire('/cms-pages', CMSPages::class)->name('cms-pages');

    // Platform Reports & Moderation
    Route::livewire('/reports', PlatformReports::class)->name('reports');
    Route::livewire('/moderation', ModerationCases::class)->name('moderation');

    // Fraud Detection
    Route::livewire('/fraud-cases', FraudCases::class)->name('fraud-cases');

    // Feature Flags
    Route::livewire('/feature-flags', FeatureFlags::class)->name('feature-flags');
});
