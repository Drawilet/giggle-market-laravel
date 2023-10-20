<div class="px-10 mt-8 grid grid-cols-1 gap-4">

    <x-dialog-modal wire:model="successModal">
        <x-slot name="title">

        </x-slot>
        <x-slot name="content">
            <div>
                <p>Product added to the cart successfully</p>
                <div class=" px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click="closeSuccessModal()" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">Close</button>
                    </span>
                </div>
            </div>

        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-dialog-modal>

    @foreach ($orderedProducts as $store)
        <details class="mb-4 p-4 bg-gray-100 dark:bg-gray-800 rounded-md" open>
            <summary class="text-white text-lg font-semibold">
                {{ $stores->where('id', $store[0]['store_id'])->first()->name }}
            </summary>

            <div class="flex flex-wrap justify-start gap-4 mt-4">
                @foreach ($store as $product)
                    @php
                        $stock = $this->getStock($product);
                    @endphp
                    <a href="/products/{{ $product['id'] }}"
                        class="bg-white dark:bg-gray-800 shadow-md rounded-md w-56 p-2">
                        <img src="{{ "/storage/products/{$product['id']}/{$product['photo']}" }}"
                            alt="{{ "{$product['photo']} photo" }}" class="w-full max-h-40 object-contain rounded-md">

                        <div class="mt-2 text-white">{{ $product['title'] }}</div>
                        <div class="text-xl text-white">${{ $product['price'] }}</div>
                        <div class="text-gray-600">Stock: {{ $stock }} <span
                                class="text-gray-700">({{ $product['stock'] }})</span>
                        </div>

                        <button wire:click.stop="addProduct({{ $product['id'] }})"
                            class="block ml-auto mb-1 mr-1 {{ $stock == 0 ? 'bg-slate-500 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600' }} text-white font-bold py-2 px-4 rounded-sm mt-2">
                            <i class="fa-solid fa-plus mr-1"></i>Add</button>
                    </a>
                @endforeach
            </div>
        </details>
    @endforeach
</div>
