<?php

namespace App\Livewire\Admin;

use App\Models\Administration\FraudCase;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class FraudCases extends Component
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
        $case = FraudCase::findOrFail($caseId);
        $case->update(['status' => $status]);
        $this->dispatch('notify', message: 'Case status updated');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $cases = FraudCase::with('reported_user')
            ->when($this->search, function ($query) {
                $query->where('description', 'like', "%{$this->search}%")
                    ->orWhere('evidence', 'like', "%{$this->search}%");
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.fraud-cases', [
            'cases' => $cases,
            'statuses' => ['reported', 'investigating', 'confirmed', 'dismissed'],
        ]);
    }
}
