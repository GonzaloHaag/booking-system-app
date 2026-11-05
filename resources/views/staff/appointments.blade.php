<x-layouts::staff.app :title="__('Turnos')">
    <x-slot:buttons>
        <flux:button icon="bell" variant="primary" icon:variant="outline" class="cursor-pointer mr-2"></flux:button>
        <flux:modal.trigger name="create-appointment">
            <flux:button icon="plus" variant="primary" class="cursor-pointer">Nuevo turno</flux:button>
        </flux:modal.trigger>
        <flux:modal name="create-appointment" class="md:w-full">
            <div class="flex flex-col gap-y-6">
                <div>
                    <flux:heading size="lg">Nuevo turno</flux:heading>
                    <flux:text class="mt-2">Agrega un nuevo turno a tu lista.</flux:text>
                </div>
                {{-- <livewire:staff.appointments.create /> --}}
            </div>
        </flux:modal>
    </x-slot:buttons>

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded">
        <div class="flex items-center justify-between gap-x-6">
            <flux:input icon="magnifying-glass" placeholder="Buscar turno..." class="max-w-2xl" />
        </div>
        <livewire:staff.appointments.table />
    </div>
</x-layouts::staff.app>
