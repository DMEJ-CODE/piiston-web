<?php

namespace App\Livewire\Admin;

use App\Models\Administration\AdminAuditLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogs extends Component
{
    use WithPagination;

    public $search = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    public $showDetailsModal = false;

    public $selectedLog = null;

    public $oldValues = [];

    public $newValues = [];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field
            ? ($this->sortDirection === 'asc' ? 'desc' : 'asc')
            : 'asc';
        $this->sortField = $field;
    }

    public function viewDetails($logId)
    {
        $log = AdminAuditLog::with('administrator.user')->findOrFail($logId);
        $this->selectedLog = $log;
        $this->oldValues = $log->old_values ?? [];
        $this->newValues = $log->new_values ?? [];
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedLog = null;
        $this->oldValues = [];
        $this->newValues = [];
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $logs = AdminAuditLog::with('administrator.user')
            ->when($this->search, function ($query) {
                $query->where('action', 'like', "%{$this->search}%")
                    ->orWhere('entity_type', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

        return view('livewire.admin.audit-logs', [
            'logs' => $logs,
        ]);
    }
}
