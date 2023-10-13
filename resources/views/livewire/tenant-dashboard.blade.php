<div class="p-5 flex flex-col lg:flex-row">
    <x-dialog-modal wire:model="transferModal">
        <x-slot name="title">
            <h1 class="text-center text-2xl">Transfer</h1>
        </x-slot>
        <x-slot name="content">
            <div class="mb-4">
                <label for="user_id" class="block text-white text-sm font-bold mb-2">User</label>
                <select id="user_id" wire:model="user_id"
                    class="block w-full mt-1 p-2 border border-gray-300 bg-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white focus:outline-none">
                    <option value="{{ null }}">Select a user</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <x-text-input wire:model="amount" id="amount" type="number" label="Amount"
                max="{{ $tenant->balance }}" />

            <x-text-input wire:model="description" id="description" label="Description" />

            <div class=" px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click="transfer()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-purple-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-purple-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">Transfer</button>
                </span>

                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click="closeTransferModal()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">Cancel</button>
                </span>
            </div>
        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-dialog-modal>

    <div class="w-full mr-5 mb-4 lg:w-4/6">
        <div class="bg-slate-700 py-3 px-5 flex flex-col w-full rounded shadow-lg ">
            <span class="text-gray-300">Balance</span>
            <span class="text-gray-100 text-3xl">${{ $tenant->balance }}</span>
        </div>

        <button class="w-full bg-blue-400 hover:bg-blue-500 mt-1 py-2 rounded text-slate-50"
            wire:click="openTransferModal">
            <span class="text-base ">Transfer</span>
        </button>
    </div>

    <section class="w-full mb-4 lg:mr-5">
        <h3 class="text-lg w-full py-1 bg-slate-700 rounded text-gray-300 text-center mb-2">Sales</h3>
        <div class="flex flex-col-reverse">
            @foreach ($sales as $sale)
                @if ($sale->sale->payment_status != 'pending')
                    <div class="flex bg-slate-800 p-2 mb-2 rounded flex-col">
                        <div class="flex">
                            <div class="flex p-2 sales-center flex-col w-full">
                                <p class="text-gray-300 text-xl"> {{ $sale->description }}</p>
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
    </section>

    <section class="w-full mb-4 ">
        <h3 class="text-lg w-full py-1 bg-slate-700 rounded text-gray-300 text-center mb-2">Transactions</h3>
        <div class="flex flex-col-reverse">
            @foreach ($transactions as $transaction)
                @php
                    $payer = $this->getPayerData($transaction, $type, $tenant->id);
                @endphp

                <div class="flex bg-slate-800 p-2 mb-2 rounded flex-col">
                    <div class="flex justify-between">
                        <div class="flex items-center mb-3">
                            <div class="flex flex-col items-center">
                                <span class="text-base text-gray-300">{{ $payer['name'] }} </span>
                                <span class="text-base text-gray-400 -mt-2 capitalize">
                                    {{ $payer['type'] }}
                                </span>
                            </div>
                            <i class="fa-solid fa-arrow-right mx-3 text-gray-300"></i>

                            <div class="flex flex-col items-center">
                                <span class="text-base text-gray-300">{{ $transaction->recipient->name }} </span>
                                <span class="text-base text-gray-400 -mt-2 capitalize">
                                    {{ substr($transaction->recipient->getTable(), 0, -1) }}
                                </span>
                            </div>

                        </div>

                        <span
                            class="mr-1 text-gray-200 text-lg {{ $payer['is_mine'] ? 'text-red-500' : 'text-green-400' }}">{{ $payer['is_mine'] ? '-' : '+' }}
                            ${{ $transaction->amount }}</span>

                    </div>
                    <span class="text-gray-500 text-sm  text-right block">
                        {{ $transaction->description }} | {{ $transaction->created_at->format('d M Y, H:i') }}
                    </span>

                </div>
            @endforeach
        </div>
    </section>
</div>
