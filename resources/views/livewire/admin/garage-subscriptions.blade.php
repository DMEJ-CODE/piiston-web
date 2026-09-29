<div class="flex flex-col gap-4 pb-6">
    <x-admin.index-header title="Abonnements" subtitle="Plans et fonctionnalités" actionText="Nouveau plan" actionClick="edit()" searchModel="search" searchPlaceholder="Rechercher un plan..." />
    <div class="overflow-x-auto rounded-2xl border border-zinc-100 dark:border-white/5 bg-[var(--surface)] p-2 shadow-sm">
        <table class="w-full text-left">
            <thead><tr class="border-b border-zinc-100 dark:border-white/5"><th class="p-3 text-[10px] uppercase text-zinc-400">Plan</th><th class="p-3 text-[10px] uppercase text-zinc-400">Prix</th><th class="p-3 text-[10px] uppercase text-zinc-400">Fonctionnalités</th><th class="p-3 text-[10px] uppercase text-zinc-400">Statut</th><th></th></tr></thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-white/5">
                @foreach($plans as $plan)
                    <tr wire:key="plan-{{ $plan->id }}"><td class="p-3"><div class="text-xs font-black uppercase">{{ $plan->name }}</div><div class="text-[10px] text-zinc-400">{{ $plan->duration }}</div></td><td class="p-3 text-sm font-black">{{ number_format($plan->price, 0, ',', ' ') }} {{ $plan->currency?->code ?? 'XAF' }}</td><td class="p-3 text-[10px] text-zinc-500">{{ count($plan->features ?? []) }} modules</td><td class="p-3"><button wire:click="toggle({{ $plan->id }})" class="rounded-lg px-2 py-1 text-[9px] font-black uppercase {{ $plan->status ? 'bg-emerald-500/10 text-emerald-600' : 'bg-zinc-100 text-zinc-500' }}">{{ $plan->status ? 'Actif' : 'Inactif' }}</button></td><td class="p-3 text-right"><flux:button wire:click="edit({{ $plan->id }})" size="xs" variant="ghost" class="rounded-lg">Modifier</flux:button></td></tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3">{{ $plans->links() }}</div>
    </div>
    <flux:modal wire:model="showModal" class="rounded-2xl">
        <form wire:submit="save" class="space-y-4">
            <flux:heading size="lg">{{ $editingId ? 'Modifier le plan' : 'Nouveau plan' }}</flux:heading>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <flux:input wire:model="form.name" label="Nom" />
                <flux:input wire:model="form.price" type="number" label="Prix (XAF)" />
                <flux:select wire:model="form.duration" label="Durée"><flux:select.option value="monthly">Mensuel</flux:select.option><flux:select.option value="yearly">Annuel</flux:select.option></flux:select>
            </div>
            <flux:textarea wire:model="form.description" label="Description" rows="2" />
            <flux:field><flux:label>Fonctionnalités incluses</flux:label><div class="grid grid-cols-2 gap-2">@foreach($featureOptions as $feature)<label class="flex items-center gap-2 text-xs"><input type="checkbox" wire:model="form.features" value="{{ $feature }}" class="rounded">{{ str_replace('_', ' ', ucfirst($feature)) }}</label>@endforeach</div></flux:field>
            <flux:checkbox wire:model="form.status" label="Plan actif" />
            <div class="flex justify-end gap-2"><flux:button type="button" wire:click="$set('showModal', false)" variant="ghost" class="rounded-xl">Annuler</flux:button><flux:button type="submit" variant="primary" class="rounded-xl">Enregistrer</flux:button></div>
        </form>
    </flux:modal>
</div>
