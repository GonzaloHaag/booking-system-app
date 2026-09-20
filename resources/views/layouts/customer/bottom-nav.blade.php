<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="flex flex-col h-svh bg-white dark:bg-zinc-800">
    <header class="w-full min-h-16 flex items-center">
         <h1>Test</h1>
    </header>
    {{ $slot }}
    <nav
        class="shrink-0 z-50 flex min-h-16 w-full items-center justify-center border-t border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
        <ul>
            <li>
                <flux:link icon="home" :href="route('customer.dashboard')"
                    :current="request()->routeIs('customer.dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:link>
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
