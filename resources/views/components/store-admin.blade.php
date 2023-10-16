@if (Auth::user()->store_role == 'admin')
    {{ $slot }}
@endif
