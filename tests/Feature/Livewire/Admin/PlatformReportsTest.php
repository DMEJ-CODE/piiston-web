<?php

namespace Tests\Feature\Livewire\Admin;

use App\Livewire\Admin\PlatformReports;
use App\Models\Administration\Administrator;
use App\Models\Administration\PlatformReport;

class PlatformReportsTest extends AdminTestCase
{
    public function test_can_update_report_status()
    {
        $this->actingAsAdmin();

        $admin = Administrator::first();

        $report = PlatformReport::create([
            'report_type' => 'Financial',
            'title' => 'Existing Report',
            'description' => 'Description',
            'status' => 'pending',
            'generated_by' => $admin->id,
            'file_path' => '/reports/test.pdf',
            'generated_at' => now(),
        ]);

        $component = new PlatformReports;
        $component->updateReportStatus($report->id, 'resolved');

        $this->assertEquals('resolved', $report->fresh()->status);
    }
}
