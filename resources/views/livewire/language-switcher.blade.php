<div>
    <flux:dropdown position="bottom" align="end">
        <flux:button variant="ghost" size="sm" icon="language">
            {{ strtoupper($this->currentLocale) }}
        </flux:button>

        <flux:menu>
            @foreach($languages as $language)
                <flux:menu.item
                    wire:click="switchLocale('{{ $language->code }}')"
                    :class="$this->currentLocale === $language->code ? 'bg-zinc-100 dark:bg-white/10 font-medium' : ''"
                >
                    {{ $language->name }}
                    @if($this->currentLocale === $language->code)
                        <flux:icon name="check" class="ms-auto size-4" />
                    @endif
                </flux:menu.item>
            @endforeach
        </flux:menu>
    </flux:dropdown>
</div>
