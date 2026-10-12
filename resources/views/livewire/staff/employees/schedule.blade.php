<form wire:submit="save" class="w-full flex flex-col gap-6">
    @foreach ($form->daysOfWeek as $index => $day)
        <!-- Esto me da el indice de cada elemento del array, y en day es un array asociativo con enabled, start_time y end_time. -->
        <div class="flex items-center justify-between" wire:key="{{ $index }}">
            <div class="flex items-center gap-x-1">
                <flux:checkbox wire:model="form.daysOfWeek.{{ $index }}.enabled" />
                <flux:label>{{ $day['name'] }}</flux:label>
            </div>
            <div class="flex items-center gap-x-1">
                <flux:input type="time" class="min-w-36" wire:model="form.daysOfWeek.{{ $index }}.start_time" />
                <flux:input type="time" class="min-w-36" wire:model="form.daysOfWeek.{{ $index }}.end_time" />
            </div>
        </div>
    @endforeach
    <div class="flex">
        <flux:spacer />
        <flux:button type="submit" variant="primary" wire:loading.attr="disabled" class="cursor-pointer">Guardar horarios</flux:button>
    </div>
</form>
