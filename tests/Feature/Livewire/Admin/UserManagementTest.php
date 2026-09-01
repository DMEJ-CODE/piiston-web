<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\UserManagement;
use App\Models\User;

class UserManagementTest extends AdminTestCase
{
    public function test_can_change_user_status()
    {
        $this->actingAsAdmin();

        $user = User::factory()->create(['status' => 'active']);

        $component = new UserManagement;
        $component->changeUserStatus($user->id, 'inactive');

        $this->assertEquals('inactive', $user->fresh()->status);
    }
}
