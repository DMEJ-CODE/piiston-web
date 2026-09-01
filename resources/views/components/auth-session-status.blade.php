@props([
    'status',
])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-bold text-sm text-[var(--accent-green)] text-center p-3 bg-[var(--accent-green)]/10 rounded-xl mb-4']) }}>
        {{ $status }}
    </div>
@endif
