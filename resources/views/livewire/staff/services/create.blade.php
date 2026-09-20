<form wire:submit="save" class="flex flex-col gap-6">
    <flux:input label="Nombre" placeholder="Nombre del servicio" type="text" wire:model="form.name" />
    <flux:input label="Precio" type="number" wire:model="form.price" />
    <flux:input label="Duración" type="number" wire:model="form.duration_minutes" />
    <flux:textarea label="Descripción" placeholder="Descripción del servicio" wire:model="form.description" />
    <div class="flex">
        <flux:spacer />
        <flux:button type="submit" variant="primary" wire:loading.attr="disabled" class="cursor-pointer">Crear servicio
        </flux:button>
    </div>
</form>
