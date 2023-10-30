<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-neutral px-8 py-2']) }}>
    {{ $slot }}
</button>
