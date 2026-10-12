@props([
    'icon',
    'href' => '#',
    'current' => false,
])

<a href="{{ $href }}" @if ($current) aria-current="page" @endif
    {{ $attributes->class([
        'flex flex-col items-center gap-0.5 text-[10px] font-semibold transition-colors',
        'text-accent-content' => $current,
        'text-zinc-400 hover:text-zinc-700 dark:text-zinc-500 dark:hover:text-zinc-300' => ! $current,
    ]) }}>
    <flux:icon :icon="$icon" class="size-6" />
    {{ $slot }}
</a>
