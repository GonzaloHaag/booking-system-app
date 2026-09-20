<x-layouts::customer.bottom-nav :title="$title ?? null">
    <flux:main class="flex-1 overflow-y-auto">
        {{ $slot }}
    </flux:main>
</x-layouts::customer.bottom-nav>
