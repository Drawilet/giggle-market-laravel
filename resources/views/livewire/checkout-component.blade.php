<div class="w-4/5 lg:w-1/2 block mx-auto py-5">
    @if ($cart)
        <div>
            @foreach ($cart as $item)
                <div class="flex mb-2">
                    <div class="flex w-full">
                        <img src="{{ "/storage/products/{$item->product->id}/{$item->product->photo}" }}"
                            alt="{{ "{$item->product->photo} photo" }}" class="w-20 object-contain rounded-t-md " />

                        <div class="p-2 items-center flex-col w-full">
                            <div>
                                <p class="text-slate-600 text-xl"> {{ $item->product->name }}</p>
                                <p class="text-slate-500 -mt-2">{{ $item->product->store->name }} /
                                    {{ $item->product->category->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex text-right flex-col w-full">
                        <span class="text-lg text-slate-500">{{ $item->quantity }} X ${{ $item->product->price }} =
                            ${{ $this->getPrice($item) }}</span>

                        @foreach ($item->product->product_taxes as $product_tax)
                            <span class="text-base -mt-2 text-slate-500">
                                <span class="text-slate-700">{{ $product_tax->tax->name }}</span> +
                                ${{ $this->getTax($item, $product_tax->tax) }}</span>
                        @endforeach
                    </div>

                </div>

                <div class="mb-2 border-t border-gray-200 dark:border-gray-600"></div>
            @endforeach

        </div>

        <div class="block ">
            <span class="block w-full text-right text-white text-3xl">${{ $this->getAmount() }}</span>

            @foreach ($methods as $key => $method)
                @if ($method['allowPayments'])
                    <button wire:click="checkout('{{ $key }}')"
                        style="background-color: {{ $method['button']['bg'] }}"
                        class="w-full p-2 rounded-sm flex items-center justify-center mb-2 ">
                        <img src="{{ $method['button']['logo'] }}" alt="{{ $key }} checkout" class="w-32 ">
                    </button>
                @endif
            @endforeach
        </div>
    @else
        <p>No</p>
    @endif
</div>
