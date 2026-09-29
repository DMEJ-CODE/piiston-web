<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

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

    public function changeUserStatus($userId, $status)
    {
        $user = User::findOrFail($userId);
        $user->update(['status' => $status]);
        $this->dispatch('notify', message: 'User status updated');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $users = User::when($this->search, function ($query) {
            $search = trim($this->search);

            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ]);
    }
}
