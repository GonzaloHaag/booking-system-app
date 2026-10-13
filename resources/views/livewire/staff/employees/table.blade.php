<flux:table>
    <flux:table.columns>
        <flux:table.column>ID</flux:table.column>
        <flux:table.column>Nombre</flux:table.column>
        <flux:table.column>Télefono</flux:table.column>
        <flux:table.column>Fecha de nacimiento</flux:table.column>
        <flux:table.column>Correo electrónico</flux:table.column>
        <flux:table.column>Acciones</flux:table.column>
    </flux:table.columns>

    <flux:table.rows>

        @forelse ($employees as $employee)
            <flux:table.row wire:key="{{ $employee->id }}">
                <flux:table.cell>{{ $employee->id }}</flux:table.cell>
                <flux:table.cell>{{ $employee->name }}</flux:table.cell>
                <flux:table.cell>{{ $employee->phone }}</flux:table.cell>
                <flux:table.cell>{{ $employee->birthdate?->format('d/m/Y') }}</flux:table.cell>
                <flux:table.cell>{{ $employee->user->email }}</flux:table.cell>
                <flux:table.cell>
                    <flux:modal.trigger name="schedule-employee-{{ $employee->id }}">
                        <flux:button icon="clock" size="sm" class="cursor-pointer" color="zinc" title="Ver horario"
                            icon:variant="outline" />
                    </flux:modal.trigger>
                    <flux:modal name="schedule-employee-{{ $employee->id }}" class="md:w-full">
                        <div class="flex flex-col gap-y-6">
                            <div>
                                <flux:heading size="lg">Definir horario de {{ $employee->name }}</flux:heading>
                                <flux:text class="mt-2">Agrega un nuevo horario para este empleado.</flux:text>
                            </div>
                            <livewire:staff.employees.schedule :employee="$employee"
                                wire:key="schedule-{{ $employee->id }}" />
                        </div>
                    </flux:modal>

                    <flux:modal.trigger name="update-employee-{{ $employee->id }}">
                        <flux:button icon="pencil" size="sm" color="green" title="Editar" icon:variant="outline"
                            class="text-green-600! cursor-pointer" />
                    </flux:modal.trigger>

                    <flux:modal name="update-employee-{{ $employee->id }}" class="md:w-full">
                        <div class="flex flex-col gap-y-6">
                            <div>
                                <flux:heading size="lg">Editar empleado {{ $employee->name }}</flux:heading>
                                <flux:text class="mt-2">Colocá los detalles del empleado</flux:text>
                            </div>
                            <livewire:staff.employees.update :employee="$employee" :wire:key="'update-'.$employee->id" />
                        </div>
                    </flux:modal>



                    <flux:modal.trigger name="delete-employee-{{ $employee->id }}">
                        <flux:button icon="trash" size="sm" color="red" title="Borrar" icon:variant="outline"
                            class="text-red-600! cursor-pointer" />
                    </flux:modal.trigger>

                    <flux:modal name="delete-employee-{{ $employee->id }}" class="min-w-88">
                        <div class="flex flex-col gap-y-6">
                            <div>
                                <flux:heading size="lg">Estás seguro?</flux:heading>
                                <flux:text class="mt-2">
                                    Esta acción no se puede deshacer. Eliminarás al empleado y todos sus datos
                                    asociados.
                                </flux:text>
                            </div>
                            <div class="flex gap-2">
                                <flux:spacer />
                                <flux:modal.close>
                                    <flux:button variant="ghost">Cancelar</flux:button>
                                </flux:modal.close>
                                <flux:button variant="danger" class="cursor-pointer"
                                    wire:click="delete({{ $employee->id }})">Eliminar empleado</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                </flux:table.cell>

            </flux:table.row>
        @empty
            <flux:table.row>
                <flux:table.cell colspan="6" class="text-left">
                    No hay empleados registrados.
                </flux:table.cell>
            </flux:table.row>
        @endforelse
    </flux:table.rows>
</flux:table>
