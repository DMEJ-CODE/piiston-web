<?php

namespace App\Livewire\Admin;

use App\Models\ApplicationErrorLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicationErrors extends Component
{
    use WithPagination;

    public string $search = '';

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.application-errors', ['errors' => ApplicationErrorLog::query()->when($this->search, fn ($q) => $q->where('message', 'like', "%{$this->search}%")->orWhere('exception_class', 'like', "%{$this->search}%"))->latest()->paginate(25)]);
    }
}
