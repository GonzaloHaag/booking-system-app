<flux:table>
    <flux:table.columns>
        <flux:table.column>Nombre</flux:table.column>
        <flux:table.column>Télefono</flux:table.column>
        <flux:table.column>Fecha de nacimiento</flux:table.column>
        <flux:table.column>Login</flux:table.column>
        <flux:table.column>Acciones</flux:table.column>
    </flux:table.columns>

    <flux:table.rows>
        @forelse ($customers as $customer)
            <flux:table.row wire:key="{{ $customer->id }}">
                <flux:table.cell>{{ $customer->name }}</flux:table.cell>
                <flux:table.cell>{{ $customer->phone }}</flux:table.cell>
                <flux:table.cell>{{ $customer->birthdate?->format('d/m/Y') }}</flux:table.cell>
                <flux:table.cell>{{ $customer->user?->email ?? 'Sin cuenta' }}</flux:table.cell>
                <flux:table.cell>

                    <flux:modal.trigger name="update-customer-{{ $customer->id }}">
                        <flux:button icon="pencil" size="sm" color="green" title="Editar" icon:variant="outline"
                            class="text-green-600! cursor-pointer" />
                    </flux:modal.trigger>

                    <flux:modal name="update-customer-{{ $customer->id }}" class="md:w-full">
                        <div class="flex flex-col gap-y-6">
                            <div>
                                <flux:heading size="lg">Editar cliente {{ $customer->name }}</flux:heading>
                                <flux:text class="mt-2">Colocá los detalles del cliente</flux:text>
                            </div>
                            <livewire:staff.customers.update :customer="$customer" :wire:key="'update-'.$customer->id" />
                        </div>
                    </flux:modal>

                    @if ($customer->user === null)
                        <flux:modal.trigger name="create-customer-account-{{ $customer->id }}">
                            <flux:button icon="user-plus" size="sm" color="green" title="Crear cuenta"
                                icon:variant="outline" class="cursor-pointer" />
                        </flux:modal.trigger>

                        <flux:modal name="create-customer-account-{{ $customer->id }}" class="md:w-full">
                            <div class="flex flex-col gap-y-6">
                                <div>
                                    <flux:heading size="lg">Crear cuenta para cliente {{ $customer->name }}
                                    </flux:heading>
                                    <flux:text class="mt-2">Colocá los detalles de la cuenta</flux:text>
                                </div>
                                <livewire:staff.customers.create-account :customer="$customer"
                                    :wire:key="'create-account-'.$customer->id" />
                            </div>
                        </flux:modal>
                    @endif

                    <flux:modal.trigger name="delete-customer-{{ $customer->id }}">
                        <flux:button icon="trash" size="sm" color="red" title="Borrar" icon:variant="outline"
                            class="text-red-600! cursor-pointer" />
                    </flux:modal.trigger>

                    <flux:modal name="delete-customer-{{ $customer->id }}" class="min-w-88">
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
                                    wire:click="delete({{ $customer->id }})">Eliminar cliente</flux:button>
                            </div>
                        </div>
                    </flux:modal>

                </flux:table.cell>
            </flux:table.row>
        @empty
            <flux:table.row>
                <flux:table.cell colspan="5" class="text-left">
                    No hay clientes registrados.
                </flux:table.cell>
            </flux:table.row>
        @endforelse
    </flux:table.rows>
</flux:table>
