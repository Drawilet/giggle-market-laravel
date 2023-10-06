<div class="p-5 flex">
    <div class="bg-slate-700 py-3 px-5 flex flex-col w-72 rounded shadow-lg">
        <span class="text-gray-300">Balance</span>
        <span class="text-gray-100 text-3xl">${{ $tenant->balance }}</span>
    </div>


    <div class="w-4/5 lg:w-1/2 block mx-auto py-5">
        @foreach ($sales as $sale)
            @if ($sale->sale->payment_status != 'pending')
                <div class="flex bg-slate-800 p-2 mb-2 rounded flex-col">
                    <div class="flex">
                        <div class="flex p-2 sales-center flex-col w-full">
                            <p class="text-slate-600 text-xl"> {{ $sale->description }}</p>
                            <p class="text-slate-500 -mt-2">{{ $sale->tenant_name }}</p>
                        </div>

                        <div class="flex text-right flex-col w-full">
                            <span class="text-lg text-slate-500">{{ $sale->quantity }} X ${{ $sale->price }} =
                                ${{ $sale->quantity * $sale->price }}</span>
                        </div>
                    </div>


                    <h4 class="text-gray-500 text-sm text-right">{{ $sale->created_at->format('d M Y, H:i') }}</h4>


                </div>
            @endif
        @endforeach
    </div>
</div>
