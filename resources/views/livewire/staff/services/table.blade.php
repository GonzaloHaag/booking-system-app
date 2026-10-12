<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($services as $service)
        <flux:card variant="soft" class="py-4 flex flex-col gap-y-2" wire:key="service-{{ $service->id }}">
            <div class="flex items-center justify-between">
                <flux:heading size="lg" class="font-medium">{{ $service->name }}</flux:heading>
                <div class="flex items-center gap-x-1">
                    <flux:modal.trigger name="update-service-{{ $service->id }}">
                        <flux:button variant="filled" icon="pencil" title="Editar servicio" class="cursor-pointer"></flux:button>
                    </flux:modal.trigger>

                    <flux:modal name="update-service-{{ $service->id }}" class="md:w-full">
                        <div class="flex flex-col gap-y-6">
                            <div>
                                <flux:heading size="lg">Editar servicio {{ $service->name }}</flux:heading>
                                <flux:text class="mt-2">Colocá los detalles del servicio</flux:text>
                            </div>
                            <livewire:staff.services.update :service="$service" :wire:key="'update-'.$service->id" />
                        </div>
                    </flux:modal>




                    <flux:modal.trigger name="delete-service-{{ $service->id }}">
                        <flux:button variant="danger" icon="trash" title="Eliminar servicio" class="cursor-pointer"></flux:button>
                    </flux:modal.trigger>

                    <flux:modal name="delete-service-{{ $service->id }}" class="min-w-88">
                        <div class="flex flex-col gap-y-6">
                            <div>
                                <flux:heading size="lg">Estás seguro?</flux:heading>

                                <flux:text class="mt-2">
                                    Esta acción no se puede deshacer.
                                </flux:text>
                            </div>

                            <div class="flex gap-2">
                                <flux:spacer />

                                <flux:modal.close>
                                    <flux:button variant="ghost">Cancelar</flux:button>
                                </flux:modal.close>

                                <flux:button variant="danger" class="cursor-pointer"
                                    wire:click="delete({{ $service->id }})">Eliminar servicio</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                </div>
            </div>
            <flux:text class="mt-1">{{ $service->duration_minutes }} minutos</flux:text>
            <flux:badge color="lime" class="w-max">{{ $service->formatted_price }}</flux:badge>
        </flux:card>
    @empty
        <flux:card variant="soft" class="py-4">
            <flux:text class="font-medium">No se encontraron servicios</flux:text>
        </flux:card>
    @endforelse
</div>
