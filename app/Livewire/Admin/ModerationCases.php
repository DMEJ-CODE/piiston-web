<?php

namespace App\Livewire\Admin;

use App\Models\Administration\ModerationCase;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ModerationCases extends Component
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

    public function updateCaseStatus($caseId, $status)
    {
        $case = ModerationCase::findOrFail($caseId);
        $case->update(['status' => $status]);
        $this->dispatch('notify', message: 'Case status updated');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $cases = ModerationCase::when($this->search, function ($query) {
            $query->where('reason', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%");
        })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.moderation-cases', [
            'cases' => $cases,
            'statuses' => ['open', 'under_review', 'resolved', 'appealed'],
        ]);
    }
}
