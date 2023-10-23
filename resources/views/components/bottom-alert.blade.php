@props(['on' => '', 'type' => 'info'])

@php
    $colors = [
        'error' => '#e53e3e', // "bg-red-600
    'success' => '#38a169', // "bg-green-600
        'warning' => '#dd6b20', // "bg-yellow-600
    'info' => '#3182ce', // "bg-blue-600
    ];
    $color = $colors[$type];
@endphp

<div x-data="{ shown: false, timeout: null }" x-init="@this.on('{{ $on }}', () => {
    clearTimeout(timeout);
    shown = true;
    timeout = setTimeout(() => { shown = false }, 2000);
})" x-show.transition.out.opacity.duration.1500ms="shown"
    x-transition:leave.opacity.duration.1500ms style="display: none;"
    class="p-3 rounded  fixed bottom-0 right-0 bg-slate-800 mb-2 mr-2 flex flex-col">

    <span class="text-gray-200 mb-2 px-2"> {{ $slot->isEmpty() ? 'Saved.' : $slot }}</span>

    <span class="w-full rounded border-b" style="border-color: {{ $color }}"></span>
</div>
