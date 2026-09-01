<?php

namespace App\Livewire\Admin;

use App\Models\Administration\FeatureFlag;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class FeatureFlags extends Component
{
    use WithPagination;

    public $search = '';

    public $showModal = false;

    public $editingFlag = null;

    public $form = [
        'feature_name' => '',
        'description' => '',
        'enabled' => false,
        'rollout_percentage' => 100,
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openModal($flagId = null)
    {
        if ($flagId) {
            $this->editingFlag = FeatureFlag::findOrFail($flagId);
            $this->form = [
                'feature_name' => $this->editingFlag->feature_name,
                'description' => $this->editingFlag->description,
                'enabled' => $this->editingFlag->enabled,
                'rollout_percentage' => $this->editingFlag->rollout_percentage,
            ];
        } else {
            $this->form = ['feature_name' => '', 'description' => '', 'enabled' => false, 'rollout_percentage' => 100];
        }
        $this->showModal = true;
    }

    public function saveFlag()
    {
        $this->validate([
            'form.feature_name' => 'required|string|max:100|unique:feature_flags,feature_name'.($this->editingFlag ? ','.$this->editingFlag->id : ''),
            'form.description' => 'nullable|string|max:500',
            'form.enabled' => 'boolean',
            'form.rollout_percentage' => 'required|integer|min:0|max:100',
        ]);

        if ($this->editingFlag) {
            $this->editingFlag->update($this->form);
            $this->dispatch('notify', message: 'Feature flag updated');
        } else {
            FeatureFlag::create($this->form);
            $this->dispatch('notify', message: 'Feature flag created');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function deleteFlag($flagId)
    {
        FeatureFlag::findOrFail($flagId)->delete();
        $this->dispatch('notify', message: 'Feature flag deleted');
    }

    public function resetForm()
    {
        $this->form = ['feature_name' => '', 'description' => '', 'enabled' => false, 'rollout_percentage' => 100];
        $this->editingFlag = null;
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $flags = FeatureFlag::when($this->search, function ($query) {
            $query->where('feature_name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%");
        })
            ->paginate(15);

        return view('livewire.admin.feature-flags', [
            'flags' => $flags,
        ]);
    }
}
