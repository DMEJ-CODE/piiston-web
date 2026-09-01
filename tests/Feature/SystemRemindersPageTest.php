<?php

use App\Models\User;

test('authenticated users can view the system reminders page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('admin.system.reminders'));

    $response->assertOk();
    $response->assertSee('System Reminders');
});
