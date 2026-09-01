<?php

namespace App\Livewire\SystemAgent;

use App\Models\AI\AiModel;
use App\Models\Reminder;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class RemindersPanel extends Component
{
    use WithPagination;

    public $showForm = false;

    public $editingId = null;

    public $title = '';

    public $body = '';

    public $run_at = null;

    public $cron_expression = '';

    public $channel = 'in-app';

    public $enabled = false;

    public $ai_model_id = null;

    public $search = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'body' => 'nullable|string',
        'run_at' => 'nullable|date',
        'cron_expression' => 'nullable|string',
        'channel' => 'nullable|string',
        'enabled' => 'nullable|boolean',
        'ai_model_id' => 'nullable|exists:ai_models,id',
    ];

    public function render()
    {
        $reminders = Reminder::query()
            ->where('title', 'like', "%{$this->search}%")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $aiModels = AiModel::all();

        return view('livewire.system-agent.reminders-panel', [
            'reminders' => $reminders,
            'aiModels' => $aiModels,
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(Reminder $reminder)
    {
        $this->editingId = $reminder->id;
        $this->title = $reminder->title;
        $this->body = $reminder->body;
        $this->run_at = $reminder->run_at?->format('Y-m-d\TH:i');
        $this->cron_expression = $reminder->cron_expression ?? '';
        $this->channel = $reminder->channel ?? 'in-app';
        $this->enabled = $reminder->enabled;
        $this->ai_model_id = $reminder->ai_model_id;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $reminder = Reminder::find($this->editingId);
            $reminder->update([
                'title' => $this->title,
                'body' => $this->body,
                'run_at' => $this->run_at ? Carbon::parse($this->run_at) : null,
                'cron_expression' => $this->cron_expression ?: null,
                'channel' => $this->channel,
                'enabled' => $this->enabled,
                'ai_model_id' => $this->ai_model_id,
            ]);
            session()->flash('message', 'Reminder updated successfully.');
        } else {
            Reminder::create([
                'title' => $this->title,
                'body' => $this->body,
                'run_at' => $this->run_at ? Carbon::parse($this->run_at) : null,
                'cron_expression' => $this->cron_expression ?: null,
                'channel' => $this->channel,
                'enabled' => $this->enabled,
                'ai_model_id' => $this->ai_model_id,
            ]);
            session()->flash('message', 'Reminder created successfully.');
        }

        $this->resetForm();
    }

    public function toggleEnabled($id)
    {
        $reminder = Reminder::find($id);
        if ($reminder) {
            $reminder->update(['enabled' => ! $reminder->enabled]);
        }
    }

    public function delete($id)
    {
        $reminder = Reminder::find($id);
        if ($reminder) {
            $reminder->delete();
            session()->flash('message', 'Reminder deleted.');
        }
    }

    public function resetForm()
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->title = '';
        $this->body = '';
        $this->run_at = null;
        $this->cron_expression = '';
        $this->channel = 'in-app';
        $this->enabled = false;
        $this->ai_model_id = null;
    }
}
