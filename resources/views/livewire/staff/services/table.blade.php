<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($services as $service)
        <flux:card variant="soft" class="py-4 flex flex-col gap-y-2" wire:key="{{ $service->id }}">
            <div class="flex items-center justify-between">
                <flux:heading size="lg" class="font-medium">{{ $service->name }}</flux:heading>
                <div class="flex items-center gap-x-1">
                    <flux:button icon="pencil" title="Editar servicio"></flux:button>
                    <flux:button icon="trash" title="Eliminar servicio"></flux:button>
                </div>
            </div>
            <flux:text class="mt-1">{{ $service->duration_minutes }} minutos</flux:heading>
            <flux:badge color="lime" class="w-max">{{ $service->formatted_price }}</flux:badge>
        </flux:card>
    @empty
        <flux:card variant="soft" class="py-4">
            <flux:text class="font-medium">No se encontraron servicios</flux:text>
        </flux:card>
    @endforelse
</div>
