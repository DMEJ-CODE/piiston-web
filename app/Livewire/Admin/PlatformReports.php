<?php

namespace App\Livewire\Admin;

use App\Models\Administration\PlatformReport;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class PlatformReports extends Component
{
    use WithPagination;

    public $search = '';

    public $status = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updateReportStatus($reportId, $status)
    {
        $report = PlatformReport::findOrFail($reportId);
        $report->update(['status' => $status]);
        $this->dispatch('notify', message: 'Report status updated');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $reports = PlatformReport::when($this->search, function ($query) {
            $query->where('title', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%");
        })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.platform-reports', [
            'reports' => $reports,
            'statuses' => ['pending', 'in_review', 'resolved', 'dismissed'],
        ]);
    }
}
