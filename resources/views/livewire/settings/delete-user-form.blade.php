<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('Borrar cuenta') }}</flux:heading>
        <flux:subheading>{{ __('Borrar cuenta y todos sus recursos') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('Eliminar cuenta') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form method="POST" wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Estás seguro de que quieres eliminar tu cuenta?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Una vez que se elimine su cuenta, todos sus recursos y datos se borrarán de forma permanente. Por favor, introduzca su contraseña para confirmar que desea eliminar su cuenta definitivamente.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('Contraseña')" type="password" viewable />

            <div class="flex justify-end gap-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('Eliminar cuenta') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
