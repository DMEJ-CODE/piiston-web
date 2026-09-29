<x-layouts::app :title="__('payments.Payment canceled')">
    <div class="mx-auto flex max-w-lg flex-col items-center gap-4 rounded-2xl border border-amber-200 bg-[var(--surface)] p-8 text-center shadow-sm dark:border-amber-500/20">
        <div class="flex size-14 items-center justify-center rounded-full bg-amber-500/10 text-amber-600"><flux:icon icon="minus" class="size-7" /></div>
        <h1 class="text-lg font-black uppercase">Paiement annulé</h1>
        <p class="text-sm text-zinc-500">Le paiement a été annulé ou a expiré.</p>
        <flux:button href="{{ route('garage.settings.index') }}" variant="primary" class="rounded-xl">Retour aux paramètres</flux:button>
    </div>
</x-layouts::app>
