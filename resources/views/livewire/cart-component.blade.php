<div class="flex justify-end items-center ml-auto">
    <div class="ml-3 relative">
        <x-dropdown align="right" width="w-80">
            <x-slot name="trigger">
                <div class="relative p-3 cursor-pointer">
                    <i class="fa-solid fa-cart-shopping text-slate-600 hover:text-slate-500 text-xl"></i>
                    <span
                        class="absolute bottom-0 right-0 py-1 px-2 text-white text-xs bg-slate-900 rounded-full">{{ $totalQuantity }}</span>
                </div>
            </x-slot>
            <x-slot name="content">
                @if (!$cart->isEmpty())
                    <div class="w-full p-2">
                        @foreach ($cart as $item)
                            <div class="flex flex-col mb-2">
                                <section class="flex">
                                    <div>
                                        <img src="{{ "/storage/products/{$item->product->id}/{$item->product->photo}" }}"
                                            alt="{{ "{$item->product->photo} photo" }}"
                                            class="w-full max-h-24 object-contain rounded-t-md" />
                                    </div>

                                    <div class="p-2 flex items-center flex-col">
                                        <div>
                                            <p class="text-slate-800 text-xl leading-none">{{ $item->product->description }}</p>
                                            <p class="text-slate-500 ">{{ $item->product->tenant->name }} /
                                                {{ $item->product->category->name }}</p>
                                        </div>

                                        <div class="flex mt-2">
                                            <button wire:click.stop="handleItem({{ $item }}, 'decrease')"
                                                class="text-slate-800 border border-slate-700 bg-slate-600 px-4">-</button>
                                            <span
                                                class="text-slate-800 border border-slate-700 bg-slate-600 px-4">{{ $item['quantity'] }}</span>
                                            <button wire:click.stop="handleItem({{ $item }}, 'increase')"
                                                class="text-slate-800 border border-slate-700 bg-slate-600 px-4 {{ $item->product->stock == $item->quantity ? 'opacity-50 cursor-not-allowed' : '' }}">+</button>
                                        </div>
                                    </div>
                                </section>

                                <section class="flex">
                                    <button wire:click.stop="handleItem({{ $item }}, 'remove')"
                                        class="block bg-red-500 hover:bg-red-700 text-white text-xs py-1 w-full rounded-sm">Remove</button>
                                    <button wire:click.stop="buy({{ $item['id'] }})"
                                        class="block bg-green-500 hover:bg-green-600 text-white text-xs py-1 w-full rounded-sm">Buy
                                        now</button>
                                </section>
                            </div>

                            <div class="mb-2 border-t border-gray-200 dark:border-gray-600"></div>
                        @endforeach
                    </div>
                    <button wire:click="buy()"
                        class="block bg-green-500 hover:bg-green-600 text-white text-sm py-2 w-11/12 mx-auto mb-2 rounded-sm">
                        <i class="fa-solid fa-cart-shopping mr-1"></i>
                        Continue shopping
                    </button>
                @else
                    <p class="text-slate-200 text-lg text-center">Your cart is empty.</p>
                @endif
            </x-slot>
        </x-dropdown>
    </div>
</div>
