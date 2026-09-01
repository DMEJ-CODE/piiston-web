<?php

namespace App\Livewire\Admin;

use App\Models\Administration\AdminPermission;
use App\Models\Administration\AdminRole;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class AdminRoles extends Component
{
    use WithPagination;

    public $search = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    public $showModal = false;

    public $editingRole = null;

    public $form = [
        'name' => '',
        'description' => '',
        'level' => 1,
        'status' => true,
        'permissions' => [],
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

    public function openModal($roleId = null)
    {
        if ($roleId) {
            $this->editingRole = AdminRole::findOrFail($roleId);
            $this->form = [
                'name' => $this->editingRole->name,
                'description' => $this->editingRole->description,
                'level' => $this->editingRole->level,
                'status' => $this->editingRole->status,
                'permissions' => $this->editingRole->permissions->pluck('id')->toArray(),
            ];
        } else {
            $this->form = ['name' => '', 'description' => '', 'level' => 1, 'status' => true, 'permissions' => []];
        }
        $this->showModal = true;
    }

    public function saveRole()
    {
        $this->validate([
            'form.name' => 'required|string|max:100|unique:admin_roles,name'.($this->editingRole ? ','.$this->editingRole->id : ''),
            'form.description' => 'nullable|string|max:500',
            'form.level' => 'required|integer|min:1',
            'form.status' => 'boolean',
            'form.permissions' => 'array',
        ]);

        if ($this->editingRole) {
            $this->editingRole->update([
                'name' => $this->form['name'],
                'description' => $this->form['description'],
                'level' => $this->form['level'],
                'status' => $this->form['status'],
            ]);
            $this->editingRole->permissions()->sync($this->form['permissions']);
            $this->dispatch('notify', message: 'Role updated successfully');
        } else {
            $role = AdminRole::create([
                'name' => $this->form['name'],
                'description' => $this->form['description'],
                'level' => $this->form['level'],
                'status' => $this->form['status'],
            ]);
            $role->permissions()->attach($this->form['permissions']);
            $this->dispatch('notify', message: 'Role created successfully');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteRole($roleId)
    {
        $role = AdminRole::findOrFail($roleId);
        $role->permissions()->detach();
        $role->administrators()->detach();
        $role->delete();
        $this->dispatch('notify', message: 'Role deleted successfully');
    }

    public function resetForm()
    {
        $this->form = ['name' => '', 'description' => '', 'level' => 1, 'status' => true, 'permissions' => []];
        $this->editingRole = null;
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $roles = AdminRole::with('permissions')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $permissions = AdminPermission::where('status', true)->get();

        return view('livewire.admin.admin-roles', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }
}
