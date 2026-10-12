@if (auth()->user()->isStaff())
    <x-layouts::staff.app :title="$title ?? null">
        {{ $slot }}
    </x-layouts::staff.app>
@else
    <x-layouts::customer.app :title="$title ?? null">
        {{ $slot }}
    </x-layouts::customer.app>
@endif
