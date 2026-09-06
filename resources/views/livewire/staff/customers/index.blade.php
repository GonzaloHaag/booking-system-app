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
                <flux:table.cell>{{ $customer->birthdate }}</flux:table.cell>
                <flux:table.cell>{{ $customer->user?->email ?? 'Sin cuenta' }}</flux:table.cell>
                <flux:table.cell>
                    <flux:button icon="pencil" size="sm" class="cursor-pointer" />
                    <flux:button icon="trash" size="sm" class="cursor-pointer" />
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
