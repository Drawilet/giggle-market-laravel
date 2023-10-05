<div class="py-2">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Purchases
        </h2>
    </x-slot>

    <div class="w-4/5 lg:w-1/2 block mx-auto py-5">
        @foreach ($purchases as $purchase)
            <div class="bg-slate-800 p-2 mb-2 rounded">
                <div class="flex items-center">
                    <h4 class="text-gray-300 text-xl ml-2 flex">
                        #{{ $purchase->id }}
                        <span class="ml-2 text-slate-300">({{ $purchase->payment_status }})</span>
                    </h4>

                    <span
                        class="block w-full text-gray-500 text-sm text-right">{{ $purchase->created_at->format('d M Y, H:i') }}</span>

                </div>
                @foreach ($purchase->descriptions as $item)
                    <div class="flex mb-2">
                        <div class="flex w-full">
                            <div class="p-2 items-center flex-col w-full">
                                <div>
                                    <p class="text-slate-600 text-xl"> {{ $item->description }}</p>
                                    <p class="text-slate-500 -mt-2">{{ $item->tenant_name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex text-right flex-col w-full">
                            <span class="text-lg text-slate-500">{{ $item->quantity }} X ${{ $item->price }} =
                                ${{ $item->amount }}</span>
                        </div>

                    </div>

                    <div class="mb-2 border-t border-gray-200 dark:border-gray-600"></div>
                @endforeach

                <span class="text-gray-400 text-right block text-lg">${{ $purchase->amount }}</span>
            </div>
        @endforeach
    </div>



</div>
