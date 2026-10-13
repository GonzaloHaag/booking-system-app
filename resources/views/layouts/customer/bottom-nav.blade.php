<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="flex h-svh flex-col bg-white dark:bg-zinc-800">
    <header class="flex shrink-0 items-center justify-between px-6 pt-4 pb-2">
        <x-app-logo :href="route('customer.dashboard')" wire:navigate />

        <flux:dropdown position="bottom" align="end">
            <flux:profile :initials="auth()->user()->initials()" />

            <flux:menu>
                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                    {{ __('Configuración') }}
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Cerrar sesión') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </header>

    {{ $slot }}

    <nav aria-label="{{ __('Navegación principal') }}"
        class="z-50 shrink-0 border-t border-zinc-200 bg-zinc-50/95 px-3.5 pt-2.5 pb-[max(1rem,env(safe-area-inset-bottom))] backdrop-blur dark:border-zinc-700 dark:bg-zinc-900/95">
        <ul class="flex items-center justify-around">
            <li>
                <x-customer.nav-item icon="home" :href="route('customer.dashboard')" wire:navigate
                    :current="request()->routeIs('customer.dashboard')">
                    {{ __('Inicio') }}
                </x-customer.nav-item>
            </li>
            <li>
                {{-- TODO: apuntar a la ruta de "Mis turnos" cuando exista --}}
                <x-customer.nav-item icon="calendar-days">
                    {{ __('Mis turnos') }}
                </x-customer.nav-item>
            </li>
            <li class="-mt-6">
                {{-- TODO: apuntar al flujo de reserva cuando exista --}}
                <a href="#" aria-label="{{ __('Reservar turno') }}"
                    class="flex size-13 items-center justify-center rounded-full bg-accent text-accent-foreground shadow-lg shadow-accent/40">
                    <flux:icon.plus class="size-7" />
                </a>
            </li>
            <li>
                {{-- TODO: apuntar a la ruta de "Historial" cuando exista --}}
                <x-customer.nav-item icon="clock">
                    {{ __('Historial') }}
                </x-customer.nav-item>
            </li>
            <li>
                <x-customer.nav-item icon="user" :href="route('profile.edit')" wire:navigate
                    :current="request()->routeIs('profile.edit')">
                    {{ __('Perfil') }}
                </x-customer.nav-item>
            </li>
        </ul>
    </nav>

    @persist('toast')
        <flux:toast.group position="top center">
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
