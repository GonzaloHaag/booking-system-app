<x-layouts::customer.bottom-nav :title="$title ?? null">
    <main class="min-h-0 flex-1 overflow-y-auto px-6 py-4">
        {{ $slot }}
    </main>
</x-layouts::customer.bottom-nav>
