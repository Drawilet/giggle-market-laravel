@if (Auth::user()->tenant_role == 'admin')
    {{ $slot }}
@endif
