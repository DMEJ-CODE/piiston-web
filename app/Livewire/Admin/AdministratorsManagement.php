<?php

namespace App\Livewire\Admin;

use App\Models\Administration\Administrator;
use App\Models\Administration\AdminRole;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class AdministratorsManagement extends Component
{
    use WithPagination;

    public $search = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    public $showModal = false;

    public $editingAdmin = null;

    public $form = [
        'user_id' => null,
        'position' => '',
        'status' => true,
        'roles' => [],
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

    public function openModal($adminId = null)
    {
        if ($adminId) {
            $this->editingAdmin = Administrator::findOrFail($adminId);
            $this->form = [
                'user_id' => $this->editingAdmin->user_id,
                'position' => $this->editingAdmin->position,
                'status' => $this->editingAdmin->status,
                'roles' => $this->editingAdmin->roles->pluck('id')->toArray(),
            ];
        } else {
            $this->form = ['user_id' => null, 'position' => '', 'status' => true, 'roles' => []];
        }
        $this->showModal = true;
    }

    public function saveAdmin()
    {
        $this->validate([
            'form.user_id' => 'required|exists:users,id|unique:administrators,user_id'.($this->editingAdmin ? ','.$this->editingAdmin->id : ''),
            'form.position' => 'required|string|max:100',
            'form.status' => 'boolean',
            'form.roles' => 'array',
        ]);

        if ($this->editingAdmin) {
            $this->editingAdmin->update([
                'position' => $this->form['position'],
                'status' => $this->form['status'],
            ]);
            $this->editingAdmin->roles()->sync($this->form['roles']);
            $this->dispatch('notify', message: 'Administrator updated successfully');
        } else {
            $admin = Administrator::create([
                'user_id' => $this->form['user_id'],
                'position' => $this->form['position'],
                'status' => $this->form['status'],
            ]);
            $admin->roles()->attach($this->form['roles']);
            $this->dispatch('notify', message: 'Administrator created successfully');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteAdmin($adminId)
    {
        $admin = Administrator::findOrFail($adminId);
        $admin->roles()->detach();
        $admin->delete();
        $this->dispatch('notify', message: 'Administrator deleted successfully');
    }

    public function resetForm()
    {
        $this->form = ['user_id' => null, 'position' => '', 'status' => true, 'roles' => []];
        $this->editingAdmin = null;
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $administrators = Administrator::with(['user', 'roles'])
            ->when($this->search, function ($query) {
                $search = trim($this->search);

                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $availableUsers = User::doesntHave('administrator')->get();
        $roles = AdminRole::where('status', true)->get();

        return view('livewire.admin.administrators-management', [
            'administrators' => $administrators,
            'availableUsers' => $availableUsers,
            'roles' => $roles,
        ]);
    }
}
