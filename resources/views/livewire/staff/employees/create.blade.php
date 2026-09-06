<form wire:submit="save" class="flex flex-col gap-6">
    <flux:input :label="__('Nombre')" placeholder="Nombre del empleado" type="text" wire:model="form.name" autofocus />
    <flux:input :label="__('Fecha de nacimiento')" type="date" wire:model="form.birthdate" />
    <flux:input :label="__('Teléfono')" placeholder="Teléfono del empleado" type="text" wire:model="form.phone" />
    <flux:input :label="__('Email (debe iniciar sesión con este correo)')" placeholder="Email del empleado" type="email" wire:model="form.email" />
    <flux:input :label="__('Contraseña')" placeholder="Contraseña del empleado" type="password" wire:model="form.password" viewable />
    <flux:input :label="__('Confirmación de contraseña')" placeholder="Confirmación de contraseña del empleado" type="password" wire:model="form.password_confirmation" viewable />
    <div class="flex">
        <flux:spacer />
        <flux:button type="submit" variant="primary" wire:loading.attr="disabled" class="cursor-pointer">Crear empleado</flux:button>
    </div>
</form>
