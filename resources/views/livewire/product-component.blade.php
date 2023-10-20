<div class="px-10 py-5 lg:px-24">
    <h2 class="text-base text-gray-400 mb-2">{{ $product->store->name }} / {{ $product->category->name }}</h2>

    <div class="lg:flex">
        <div class="lg:w-1/2 lg:mr-16">
            <img src="{{ "/storage/products/{$product['id']}/{$product['photo']}" }}"
                alt="{{ "{$product['photo']} photo" }}" class="w-full object-contain rounded-md lg:max-h-fit">
        </div>

        <div class="mt-2 lg:w-1/2">
            <h1 class="text-gray-200 text-2xl">{{ $product['title'] }}</h1>

            @if ($stock > 0)
                <div class="flex items-center">
                    <label class="text-gray-200 text-base" for="quantity">Quantity</label>
                    <select wire:model="value" name="quantity" id="quantity"
                        class="bg-slate-900 border-transparent text-gray-200">
                        @php
                            $limit = $stock < 10 ? $stock : 10;
                        @endphp

                        @for ($i = 1; $i <= $limit; $i++)
                            <option value="{{ $i }}">{{ $i }}
                            </option>
                        @endfor

                        @if ($stock > 10)
                            <option value="{{ 'custom' }}">
                                custom
                            </option>
                        @endif
                    </select>
                    @if ($value == 'custom')
                        <input wire:model="quantity" type="number" placeholder="{{ $stock }}"
                            class="bg-transparent border-slate-700 border-0 border-b text-gray-200"
                            max="{{ $stock }}">
                    @endif
                </div>

                <button wire:click.stop="addProduct"
                    class="w-full block ml-auto mb-1 mr-1 {{ $stock == 0 ? 'bg-slate-500 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600' }} text-white font-bold py-2 px-4 rounded-sm mt-2">
                    <i class="fa-solid fa-plus mr-4"></i>Add</button>
            @endif
        </div>

    </div>

    <div class="mt-5">
        <p class="text-gray-300">{{ $product->description }}</p>
    </div>


</div>
