<div class="py-2">
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Purchases
        </h2>

        @if (session()->has('success'))
            <h3 class="text-green-500 mt-2">{{ session('success') }}</h2>
        @endif

        @if (session()->has('error'))
            <h3 class="text-red-500 mt-2">{{ session('error') }}</h2>
        @endif

        @if (session()->has('info'))
            <h3 class="text-gray-900 mt-2">{{ session('info') }}</h2>
        @endif
    </x-slot>

    <div class="w-4/5 lg:w-1/2 mx-auto py-5 flex flex-col-reverse">
        @foreach ($purchases as $purchase)
            <div class="bg-base-200 p-2 mb-2 rounded">
                <div class="flex items-center">
                    <h4 class="text-xl ml-2 flex">
                        #{{ $purchase->id }}
                        <span class="ml-2 "></span>
                    </h4>

                    <span
                        class="block w-full  text-sm text-right">{{ $purchase->created_at->format('d M Y, H:i') }}</span>

                </div>
                @foreach ($purchase->descriptions as $item)
                    <div class="flex mb-2">
                        <div class="flex w-full">
                            <div class="p-2 items-center flex-col w-full">
                                <div>
                                    <p class=" text-xl"> {{ $item->product->name }}</p>
                                    <p class="opacity-80 -mt-2">{{ $item->store->name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex text-right flex-col w-full">
                            <span class="text-lg ">{{ $item->quantity }} X ${{ $item->price }} =
                                ${{ $item->quantity * $item->price }}</span>
                        </div>

                    </div>

                    <div class="mb-2 border-t border-content"></div>
                @endforeach

                <div class="flex flex-col pl-3 mb-3 relative">
                    <span class="top-0 right-0 absolute text-right block text-lg">${{ $purchase->amount }}</span>

                    <h3 class="text-xl">Payment</h3>

                    <span class="text-sm">
                        Method: {{ $purchase->payment_method }}
                    </span>
                    <span class="text-sm -mt-1">
                        Status: {{ $purchase->payment_status }}
                    </span>
                </div>

                @if ($purchase->payment_status == 'pending' && $purchase->payment_id)
                    <?php
                    $method = $methods[$purchase->payment_method];
                    ?>

                    <button wire:click="payAgain('{{ $purchase->payment_method }}', '{{ $purchase->payment_id }}')"
                        style="background-color: {{ $method['button']['bg'] }}"
                        class="w-full p-2 rounded-sm flex items-center justify-center mb-2 ">
                        <img src="/{{ $method['button']['logo'] }}" alt="{{ $purchase->payment_method }} checkout"
                            class="w-32 ">
                    </button>
                @endif

            </div>
        @endforeach
    </div>



</div>
