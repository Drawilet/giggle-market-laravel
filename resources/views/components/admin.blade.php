@if (Auth::user()->role == 'admin')
    {{ $slot }}
@endif
