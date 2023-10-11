@props(['type' => 'text', 'id' => '', 'label' => '', "max"=> null])

<div class="mb-4">
    <label for="{{ $id }}" class="block text-white text-sm font-bold mb-2">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $id }}" required max="{{ $max }}"
        {{ $attributes->merge(['class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-slate-300']) }}>

    @error($id)
        <p class="text-red-600">{{ $message }}</p>
    @enderror
</div>
