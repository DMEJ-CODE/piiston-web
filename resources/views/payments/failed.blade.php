<x-layouts::app :title="__('payments.Payment failed')">
    <div class="mx-auto flex max-w-lg flex-col items-center gap-4 rounded-2xl border border-red-200 bg-[var(--surface)] p-8 text-center shadow-sm dark:border-red-500/20">
        <div class="flex size-14 items-center justify-center rounded-full bg-red-500/10 text-red-600"><flux:icon icon="x-mark" class="size-7" /></div>
        <h1 class="text-lg font-black uppercase">Paiement non confirmé</h1>
        <p class="text-sm text-zinc-500">Le paiement n’a pas été validé. Vous pouvez réessayer depuis les paramètres.</p>
        <flux:button href="{{ route('garage.settings.index') }}" variant="primary" class="rounded-xl">Réessayer</flux:button>
    </div>
</x-layouts::app>
