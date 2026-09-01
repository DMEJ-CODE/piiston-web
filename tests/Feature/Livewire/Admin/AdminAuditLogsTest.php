<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\AuditLogs;
use App\Models\Administration\AdminAuditLog;
use App\Models\Administration\Administrator;

class AdminAuditLogsTest extends AdminTestCase
{
    public function test_can_view_audit_log_details()
    {
        $this->actingAsAdmin();

        $admin = Administrator::first();

        $log = AdminAuditLog::create([
            'administrator_id' => $admin->id,
            'action' => 'TEST_ACTION',
            'entity_type' => 'User',
            'entity_id' => 1,
            'old_values' => ['status' => 'active'],
            'new_values' => ['status' => 'inactive'],
        ]);

        $component = new AuditLogs;
        $component->viewDetails($log->id);

        $this->assertTrue($component->showDetailsModal);
        $this->assertEquals('TEST_ACTION', $component->selectedLog->action);
    }
}
