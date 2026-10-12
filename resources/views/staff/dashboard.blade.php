<x-layouts::staff.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-4">
            <flux:card variant="soft" class="flex items-start justify-between">
                <div>
                    <flux:text size="sm">Turnos hoy</flux:text>
                    <flux:heading size="xl" class="mt-1">13</flux:heading>
                    <flux:badge size="sm" color="amber" class="mt-2">+2 vs. ayer</flux:badge>
                </div>
                <flux:icon name="calendar" class="size-6 text-zinc-400" />
            </flux:card>
             <flux:card variant="soft" class="flex items-start justify-between">
                <div>
                    <flux:text size="sm">Turnos por confirmar</flux:text>
                    <flux:heading size="xl" class="mt-1">20</flux:heading>
                    <flux:badge size="sm" color="amber" class="mt-2">Requiere confirmación</flux:badge>
                </div>
                <flux:icon name="calendar" class="size-6 text-zinc-400" />
            </flux:card>
             <flux:card variant="soft" class="flex items-start justify-between">
                <div>
                    <flux:text size="sm">Ingresos del mes</flux:text>
                    <flux:heading size="xl" class="mt-1">$1,234.56</flux:heading>
                    <flux:badge size="sm" color="red" class="mt-2">-10,2030.50 vs. mes pasado</flux:badge>
                </div>
                <flux:icon name="currency-dollar" class="size-6 text-zinc-400" />
            </flux:card>
             <flux:card variant="soft" class="flex items-start justify-between">
                <div>
                    <flux:text size="sm">Ingresos del dia</flux:text>
                    <flux:heading size="xl" class="mt-1">$1,234.56</flux:heading>
                    <flux:badge size="sm" color="amber" class="mt-2">+10% vs. ayer</flux:badge>
                </div>
                <flux:icon name="document-currency-dollar" class="size-6 text-zinc-400" />
            </flux:card>
        </div>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts::staff.app>
