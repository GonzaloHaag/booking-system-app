<form wire:submit="save" class="flex flex-col gap-6">
    <flux:input label="Nombre" placeholder="Nombre del cliente" type="text" wire:model="form.name" />
    <flux:input label="Fecha de nacimiento" type="date" wire:model="form.birthdate" />
    <flux:input label="Teléfono" placeholder="Teléfono del cliente" type="text" wire:model="form.phone" />
    <div class="flex">
        <flux:spacer />
        <flux:button type="submit" variant="primary" wire:loading.attr="disabled" class="cursor-pointer">Guardar cliente</flux:button>
    </div>
</form>
