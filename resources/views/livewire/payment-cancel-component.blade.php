<div>
    @if (session()->has('error'))
        <div class="a">
            {{ session('error') }}
        </div>
    @endif
</div>
