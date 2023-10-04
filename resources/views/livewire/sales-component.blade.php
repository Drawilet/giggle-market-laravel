<div class="py-2">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Sales
        </h2>
    </x-slot>

    <div class="w-4/5 lg:w-1/2 block mx-auto py-5">
        @foreach ($sales as $sale)
            <div class="flex bg-slate-800 p-2 mb-2 rounded flex-col">


                <div class="flex">
                    <div class="flex p-2 sales-center flex-col w-full">
                        <p class="text-slate-600 text-xl"> {{ $sale->description }}</p>
                        <p class="text-slate-500 -mt-2">{{ $sale->tenant_name }}</p>
                    </div>

                    <div class="flex text-right flex-col w-full">
                        <span class="text-lg text-slate-500">{{ $sale->quantity }} X ${{ $sale->price }} =
                            ${{ $sale->amount }}</span>
                    </div>
                </div>

                <h4 class="text-gray-500 text-sm text-right">{{ $sale->created_at->format('d M Y, H:i') }}</h4>
            </div>
        @endforeach
    </div>
</div>
