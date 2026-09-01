<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\FraudCases;
use App\Models\Administration\FraudCase;
use App\Models\User;

class FraudCasesTest extends AdminTestCase
{
    public function test_can_create_fraud_case()
    {
        $this->actingAsAdmin();

        $user = User::factory()->create();

        $case = FraudCase::create([
            'user_id' => $user->id,
            'case_type' => 'Payment',
            'risk_level' => 'MEDIUM',
            'status' => 'OPEN',
        ]);

        $this->assertDatabaseHas('fraud_cases', [
            'id' => $case->id,
            'case_type' => 'Payment',
        ]);
    }

    public function test_can_update_fraud_case_status()
    {
        $this->actingAsAdmin();

        $user = User::factory()->create();

        $case = FraudCase::create([
            'user_id' => $user->id,
            'case_type' => 'MultipleAccounts',
            'risk_level' => 'HIGH',
            'status' => 'reported',
        ]);

        $component = new FraudCases;
        $component->updateCaseStatus($case->id, 'investigating');

        $this->assertEquals('investigating', $case->fresh()->status);
    }

    public function test_fraud_type_accessor_works()
    {
        $this->actingAsAdmin();

        $user = User::factory()->create();

        $case = FraudCase::create([
            'user_id' => $user->id,
            'case_type' => 'SuspiciousActivity',
            'risk_level' => 'LOW',
            'status' => 'reported',
        ]);

        $this->assertEquals('SuspiciousActivity', $case->fraud_type);
    }
}
