<x-layouts::staff.app :title="__('Clientes')">
    <x-slot:buttons>
        <flux:modal.trigger name="create-customer">
            <flux:button icon="plus" variant="primary" class="cursor-pointer">Nuevo cliente</flux:button>
        </flux:modal.trigger>
        <flux:modal name="create-customer" class="md:w-full">
            <div class="flex flex-col gap-y-6">
                <div>
                    <flux:heading size="lg">Nuevo cliente</flux:heading>
                    <flux:text class="mt-2">Agrega un nuevo cliente a tu lista.</flux:text>
                </div>
                <livewire:staff.customers.create />
            </div>
        </flux:modal>
    </x-slot:buttons>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded">
        <div class="flex items-center justify-between gap-x-6">
            <flux:input icon="magnifying-glass" placeholder="Buscar cliente..." class="max-w-2xl" />
            <div class="flex items-center gap-x-4">
                <flux:button icon="arrow-down-tray" class="cursor-pointer">Exportar PDF</flux:button>
                <flux:button icon="arrow-down-tray" class="cursor-pointer">Exportar Excel</flux:button>
            </div>
        </div>
        <livewire:staff.customers.index />
    </div>
</x-layouts::staff.app>
