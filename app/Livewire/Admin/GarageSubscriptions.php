<?php

namespace App\Livewire\Admin;

use App\Models\Finance\SubscriptionPlan;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class GarageSubscriptions extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public array $form = [
        'name' => '', 'price' => 0, 'currency_id' => 1, 'duration' => 'monthly', 'description' => '', 'features' => [], 'status' => true,
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function edit(?int $id = null): void
    {
        $this->editingId = $id;
        $this->form = $id
            ? array_replace(['features' => []], SubscriptionPlan::findOrFail($id)->only(array_keys($this->form)))
            : ['name' => '', 'price' => 0, 'currency_id' => 1, 'duration' => 'monthly', 'description' => '', 'features' => [], 'status' => true];
        $this->form['features'] = (array) $this->form['features'];
        $this->showModal = true;
    }

    public function save(): void
    {
        $data = $this->validate([
            'form.name' => ['required', 'string', 'max:100'],
            'form.price' => ['required', 'numeric', 'min:0'],
            'form.currency_id' => ['required', 'exists:currencies,id'],
            'form.duration' => ['required', 'in:monthly,yearly'],
            'form.description' => ['nullable', 'string', 'max:255'],
            'form.features' => ['array'],
            'form.features.*' => ['string', 'max:80'],
            'form.status' => ['boolean'],
        ])['form'];

        SubscriptionPlan::updateOrCreate(['id' => $this->editingId], $data);
        $this->showModal = false;
        $this->reset('editingId');
        $this->dispatch('notify', message: 'Plan mis à jour.');
    }

    public function toggle(int $id): void
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->update(['status' => ! $plan->status]);
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.garage-subscriptions', [
            'plans' => SubscriptionPlan::query()->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))->latest()->paginate(15),
            'featureOptions' => ['vehicle', 'repairs', 'appointments', 'inventory', 'reports', 'ai_assistant', 'marketplace', 'fleet', 'messaging'],
        ]);
    }
}
