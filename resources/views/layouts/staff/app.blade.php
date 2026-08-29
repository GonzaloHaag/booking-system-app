<x-layouts::staff.sidebar :title="$title ?? null">
    @isset($buttons)
        <x-slot:buttons>
            {{ $buttons }}
        </x-slot:buttons>
    @endisset
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::staff.sidebar>
