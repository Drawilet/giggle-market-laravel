<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard
        </h2>
    </x-slot>


    @if (Auth::user()->tenant_role == "admin")
        @livewire("tenant-analytics-component")
    @endif
</div>
