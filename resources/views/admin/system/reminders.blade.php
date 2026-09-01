<x-layouts::app :title="__('System Reminders')">
    <div class="mx-auto max-w-7xl py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-zinc-900">System Reminders</h1>
            <p class="mt-1 text-sm text-zinc-500">AI-driven reminder automation and system notifications</p>
        </div>

        <livewire:system-agent.reminders-panel />
    </div>
</x-layouts::app>
