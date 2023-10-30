@props(['type' => 'text', 'id' => '', 'label' => '', "max"=> null])

<div class="mb-4">
    <label for="{{ $id }}" class="block text-sm font-bold mb-2">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $id }}" required max="{{ $max }}"
        {{ $attributes->merge(['class' => 'shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline bg-base-200']) }}>

    @error($id)
        <p class="text-red-600">{{ $message }}</p>
    @enderror
</div>
