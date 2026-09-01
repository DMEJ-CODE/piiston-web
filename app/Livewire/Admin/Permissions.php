<?php

namespace App\Livewire\Admin;

use App\Models\Administration\AdminPermission;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Permissions extends Component
{
    use WithPagination;

    public $search = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    public $showModal = false;

    public $editingPermission = null;

    public $form = [
        'name' => '',
        'description' => '',
        'status' => true,
    ];

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

    public function openModal($permissionId = null)
    {
        if ($permissionId) {
            $this->editingPermission = AdminPermission::findOrFail($permissionId);
            $this->form = [
                'name' => $this->editingPermission->name,
                'description' => $this->editingPermission->description,
                'status' => $this->editingPermission->status,
            ];
        } else {
            $this->form = ['name' => '', 'description' => '', 'status' => true];
        }
        $this->showModal = true;
    }

    public function savePermission()
    {
        $this->validate([
            'form.name' => 'required|string|max:100|unique:admin_permissions,name'.($this->editingPermission ? ','.$this->editingPermission->id : ''),
            'form.description' => 'nullable|string|max:500',
            'form.status' => 'boolean',
        ]);

        if ($this->editingPermission) {
            $this->editingPermission->update($this->form);
            $this->dispatch('notify', message: 'Permission updated successfully');
        } else {
            AdminPermission::create($this->form);
            $this->dispatch('notify', message: 'Permission created successfully');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deletePermission($permissionId)
    {
        $permission = AdminPermission::findOrFail($permissionId);
        $permission->roles()->detach();
        $permission->delete();
        $this->dispatch('notify', message: 'Permission deleted successfully');
    }

    public function resetForm()
    {
        $this->form = ['name' => '', 'description' => '', 'status' => true];
        $this->editingPermission = null;
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $permissions = AdminPermission::when($this->search, function ($query) {
            $query->where('name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%");
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.permissions', [
            'permissions' => $permissions,
        ]);
    }
}
