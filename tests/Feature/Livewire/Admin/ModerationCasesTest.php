<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\ModerationCases;
use App\Models\Administration\ModerationCase;
use App\Models\User;

class ModerationCasesTest extends AdminTestCase
{
    public function test_can_create_moderation_case_with_description()
    {
        $this->actingAsAdmin();

        $user = User::factory()->create();

        $case = ModerationCase::create([
            'reported_entity' => 'User',
            'entity_id' => $user->id,
            'reported_by' => $user->id,
            'reason' => 'Spam',
            'description' => 'User posted spam content',
            'priority' => 'MEDIUM',
            'status' => 'OPEN',
        ]);

        $this->assertDatabaseHas('moderation_cases', [
            'id' => $case->id,
            'description' => 'User posted spam content',
        ]);
    }

    public function test_can_update_moderation_case_status()
    {
        $this->actingAsAdmin();

        $user = User::factory()->create();

        $case = ModerationCase::create([
            'reported_entity' => 'Message',
            'entity_id' => 1,
            'reported_by' => $user->id,
            'reason' => 'Harassment',
            'description' => 'User sent harassing messages',
            'priority' => 'HIGH',
            'status' => 'OPEN',
        ]);

        $component = new ModerationCases;
        $component->updateCaseStatus($case->id, 'resolved');

        $this->assertEquals('resolved', $case->fresh()->status);
    }
}
