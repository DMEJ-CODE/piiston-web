<x-layouts::app :title="__('subscription.Feature not available')">
    <div class="mx-auto flex max-w-lg flex-col items-center gap-4 rounded-2xl border border-amber-200 bg-[var(--surface)] p-8 text-center shadow-sm dark:border-amber-500/20">
        <div class="flex size-14 items-center justify-center rounded-full bg-amber-500/10 text-amber-600"><flux:icon icon="lock-closed" class="size-7" /></div>
        <h1 class="text-lg font-black uppercase">Fonctionnalité non incluse</h1>
        <p class="text-sm text-zinc-500">Cette fonctionnalité n’est pas disponible dans votre abonnement actuel.</p>
        <flux:button href="{{ route('subscriptions.index') }}" variant="primary" class="rounded-xl">Voir les abonnements</flux:button>
    </div>
</x-layouts::app>
