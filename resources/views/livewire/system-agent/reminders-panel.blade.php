<div class="space-y-6 p-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-zinc-900">System Reminders</h1>
            <p class="mt-1 text-sm text-zinc-500">Manage AI-driven reminders and automations</p>
        </div>
        <button type="button" wire:click="create" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-500">
            + Create Reminder
        </button>
    </div>

    @if (session()->has('message'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('message') }}
        </div>
    @endif

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
        <input
            wire:model.live="search"
            type="text"
            placeholder="Search reminders by title..."
            class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
        >
    </div>

    @if ($showForm)
        <div class="rounded-xl border border-blue-200 bg-blue-50 p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-zinc-900">{{ $editingId ? 'Edit Reminder' : 'Create New Reminder' }}</h2>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700">Title</label>
                    <input wire:model="title" type="text" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" placeholder="e.g. Weekly Team Report" />
                    @error('title')
                        <span class="mt-1 block text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700">Body</label>
                    <textarea wire:model="body" rows="3" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" placeholder="Reminder description or AI instructions..."></textarea>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700">Run At (one-time)</label>
                        <input wire:model="run_at" type="datetime-local" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700">Cron Expression</label>
                        <input wire:model="cron_expression" type="text" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" placeholder="0 9 * * *" />
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700">Channel</label>
                        <select wire:model="channel" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                            <option value="in-app">In-App</option>
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                            <option value="push">Push Notification</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-zinc-700">AI Model</label>
                        <select wire:model="ai_model_id" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                            <option value="">None</option>
                            @foreach ($aiModels as $model)
                                <option value="{{ $model->id }}">{{ $model->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <label class="inline-flex items-center gap-2 text-sm text-zinc-700">
                    <input type="checkbox" wire:model="enabled" class="rounded border-zinc-300 text-blue-600 focus:ring-blue-500" />
                    Enabled
                </label>

                <div class="flex gap-2">
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                        {{ $editingId ? 'Update' : 'Create' }}
                    </button>
                    <button type="button" wire:click="resetForm" class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-100">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-zinc-200 text-left text-sm">
            <thead class="bg-zinc-50">
                <tr>
                    <th class="px-4 py-3 font-semibold text-zinc-700">Title</th>
                    <th class="px-4 py-3 font-semibold text-zinc-700">Channel</th>
                    <th class="px-4 py-3 font-semibold text-zinc-700">Schedule</th>
                    <th class="px-4 py-3 font-semibold text-zinc-700">AI Model</th>
                    <th class="px-4 py-3 font-semibold text-zinc-700">Status</th>
                    <th class="px-4 py-3 font-semibold text-zinc-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200">
                @forelse ($reminders as $reminder)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-zinc-900">{{ $reminder->title }}</div>
                            <div class="text-xs text-zinc-500">{{ \Illuminate\Support\Str::limit($reminder->body ?? 'No description', 50) }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700">
                                {{ ucfirst($reminder->channel ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-zinc-600">
                            @if ($reminder->run_at)
                                {{ $reminder->run_at->format('M d, Y H:i') }}
                            @elseif ($reminder->cron_expression)
                                <code class="rounded bg-zinc-100 px-2 py-1 text-xs">{{ $reminder->cron_expression }}</code>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-zinc-600">
                            {{ $reminder->aiModel?->name ?? 'None' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $reminder->enabled ? 'bg-green-100 text-green-700' : 'bg-zinc-100 text-zinc-700' }}">
                                {{ $reminder->enabled ? 'Enabled' : 'Disabled' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="edit({{ $reminder->id }})" class="rounded border border-zinc-300 bg-white px-2 py-1 text-xs font-medium text-zinc-700 hover:bg-zinc-100">Edit</button>
                                <button type="button" wire:click="toggleEnabled({{ $reminder->id }})" class="rounded border border-zinc-300 bg-white px-2 py-1 text-xs font-medium text-zinc-700 hover:bg-zinc-100">
                                    {{ $reminder->enabled ? 'Disable' : 'Enable' }}
                                </button>
                                <button type="button" wire:click="delete({{ $reminder->id }})" class="rounded border border-red-200 bg-red-50 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-100">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-zinc-500">No reminders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $reminders->links() }}
    </div>
</div>
