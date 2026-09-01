<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\Permissions;
use App\Models\Administration\AdminPermission;

class PermissionsTest extends AdminTestCase
{
    public function test_can_create_permission()
    {
        $this->actingAsAdmin();

        $component = new Permissions;
        $component->form = [
            'name' => 'test.permission',
            'module' => 'test',
            'action' => 'read',
            'description' => 'Test permission description',
            'status' => true,
        ];

        $component->savePermission();

        $this->assertDatabaseHas('admin_permissions', [
            'name' => 'test.permission',
            'module' => 'test',
            'action' => 'read',
            'status' => true,
        ]);
    }

    public function test_can_update_permission()
    {
        $this->actingAsAdmin();

        $permission = AdminPermission::create([
            'name' => 'existing.permission',
            'module' => 'test',
            'action' => 'read',
            'description' => 'Original',
            'status' => true,
        ]);

        $component = new Permissions;
        $component->editingPermission = $permission;
        $component->form = [
            'name' => 'existing.permission',
            'module' => 'test',
            'action' => 'read',
            'description' => 'Updated',
            'status' => false,
        ];

        $component->savePermission();

        $this->assertEquals('Updated', $permission->fresh()->description);
        $this->assertEquals(0, $permission->fresh()->status);
    }
}
