<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\FeatureFlags;
use App\Models\Administration\FeatureFlag;

class FeatureFlagsTest extends AdminTestCase
{
    public function test_can_create_feature_flag()
    {
        $this->actingAsAdmin();

        $component = new FeatureFlags;
        $component->form = [
            'feature_name' => 'TEST_FEATURE',
            'description' => 'Test feature description',
            'enabled' => true,
            'rollout_percentage' => 50,
        ];

        $component->saveFlag();

        $this->assertDatabaseHas('feature_flags', [
            'feature_name' => 'TEST_FEATURE',
            'description' => 'Test feature description',
            'rollout_percentage' => 50,
            'enabled' => true,
        ]);
    }

    public function test_can_update_feature_flag()
    {
        $this->actingAsAdmin();

        $flag = FeatureFlag::create([
            'feature_name' => 'EXISTING_FEATURE',
            'enabled' => true,
            'rollout_percentage' => 100,
        ]);

        $component = new FeatureFlags;
        $component->editingFlag = $flag;
        $component->form = [
            'feature_name' => 'EXISTING_FEATURE',
            'description' => 'Updated description',
            'enabled' => true,
            'rollout_percentage' => 25,
        ];

        $component->saveFlag();

        $this->assertEquals(25, $flag->fresh()->rollout_percentage);
        $this->assertEquals('Updated description', $flag->fresh()->description);
    }

    public function test_can_delete_feature_flag()
    {
        $this->actingAsAdmin();

        $flag = FeatureFlag::create([
            'feature_name' => 'TO_DELETE',
            'enabled' => false,
        ]);

        $component = new FeatureFlags;
        $component->deleteFlag($flag->id);

        $this->assertDatabaseMissing('feature_flags', ['id' => $flag->id]);
    }
}
