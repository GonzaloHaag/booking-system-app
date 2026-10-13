<form wire:submit="save" class="flex flex-col gap-6">
    <flux:input :label="__('Email (debe iniciar sesión con este correo)')" placeholder="Email del cliente" type="email" wire:model="form.email" autofocus />
    <flux:input :label="__('Contraseña')" placeholder="Contraseña del cliente" type="password" wire:model="form.password" viewable />
    <flux:input :label="__('Confirmación de contraseña')" placeholder="Confirmación de contraseña del cliente" type="password" wire:model="form.password_confirmation" viewable />
    <div class="flex">
        <flux:spacer />
        <flux:button type="submit" variant="primary" wire:loading.attr="disabled" class="cursor-pointer">Crear cuenta</flux:button>
    </div>
</form>
