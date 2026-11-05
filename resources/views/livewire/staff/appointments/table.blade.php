<flux:table>
    <flux:table.columns>
        <flux:table.column>Fecha</flux:table.column>
        <flux:table.column>Horario</flux:table.column>
        <flux:table.column>Cliente</flux:table.column>
        <flux:table.column>Servicio</flux:table.column>
        <flux:table.column>Duración</flux:table.column>
        <flux:table.column>Empleado</flux:table.column>
        <flux:table.column>Estado</flux:table.column>
        <flux:table.column>Acciones</flux:table.column>
    </flux:table.columns>

    <flux:table.rows>

        @forelse ($appointments as $appointment)
            <flux:table.row wire:key="appointment-{{ $appointment->id }}">
                <flux:table.cell>{{ $appointment->date->format('d/m/Y') }}</flux:table.cell>
                <flux:table.cell>{{ $appointment->start_time }} - {{ $appointment->end_time }}</flux:table.cell>
                <flux:table.cell>{{ $appointment->customer->name }}</flux:table.cell>
                <flux:table.cell>{{ $appointment->service->name }}</flux:table.cell>
                <flux:table.cell>{{ $appointment->duration_in_minutes }} min.</flux:table.cell>
                <flux:table.cell>{{ $appointment->employee->name }}</flux:table.cell>
                <flux:table.cell>
                    <flux:badge color="{{ $appointment->status->color() }}">{{ $appointment->status->label() }}</flux:badge>
                </flux:table.cell>
                <flux:table.cell>
                  Actions
                </flux:table.cell>

            </flux:table.row>
        @empty
            <flux:table.row>
                <flux:table.cell colspan="8" class="text-left">
                    No hay turnos registrados.
                </flux:table.cell>
            </flux:table.row>
        @endforelse
    </flux:table.rows>
</flux:table>
