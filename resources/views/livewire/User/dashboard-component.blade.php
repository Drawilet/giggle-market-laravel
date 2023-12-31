<div class="p-5 flex flex-col lg:flex-row">
    <x-dialog-modal wire:model="withdrawModal">
        <x-slot name="title">
            <h1 class="text-center text-2xl">Withdraw</h1>
        </x-slot>
        <x-slot name="content">
            <div class="mb-4">
                <label for="method" class="block text-white text-sm font-bold mb-2">Method</label>
                <select id="method" wire:model="method"
                    class="block w-full mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white focus:outline-none">
                    <option value="{{ null }}">Select a method</option>
                    @foreach ($methods as $key => $method)
                        @if ($method['allowWithdraw'])
                            <option value="{{ $key }}">{{ $method['label'] }}</option>
                        @endif
                    @endforeach
                </select>
                @error('method')
                    <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <x-text-input wire:model="amount" id="amount" type="number" label="Amount" max="{{ $user->balance }}" />
            <div class=" px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click="withdraw()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-purple-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-purple-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">Withdraw</button>
                </span>

                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click="closeWithdrawModal()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">Cancel</button>
                </span>
            </div>
        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-dialog-modal>

    <div class="w-full mr-5 mb-4 lg:w-4/6">
        <div class="bg-base-200 py-3 px-5 flex flex-col w-full rounded shadow-lg ">
            <span class="">Balance</span>
            <span class=" text-3xl">${{ $user->balance }}</span>
        </div>

        <button class="w-full bg-green-400 hover:bg-green-500 mt-1 py-2 rounded text-slate-50"
            wire:click="openWithdrawModal">
            <span class="text-base ">Withdraw</span>
        </button>
    </div>

    <section class="w-full mb-4 ">
        <h3 class="text-lg w-full py-1 bg-base-300 rounded text-center mb-2">Transactions</h3>
        <div class="flex flex-col-reverse">
            @foreach ($transactions as $transaction)
                @php
                    $payer = $this->getPayerData($transaction, $type, $user->id);
                    $recipient = $this->getRecipientData($transaction, $type, $user->id);

                @endphp

                <div class="flex bg-base-200 p-2 mb-2 rounded flex-col">
                    <div class="flex justify-between">
                        <div class="flex items-center mb-3">
                            <div class="flex flex-col items-center">
                                <span class="text-base ">{{ $payer['name'] }} </span>
                                <span class="text-base opacity-80 -mt-2 capitalize">
                                    {{ $payer['type'] }}
                                </span>
                            </div>
                            <i class="fa-solid fa-arrow-right mx-3 "></i>

                            <div class="flex flex-col items-center">
                                <span class="text-base">{{ $recipient['name'] }} </span>
                                <span class="text-base opacity-80 -mt-2 capitalize">
                                    {{ $recipient['type'] }}
                                </span>
                            </div>

                        </div>

                        <span
                            class="mr-1  text-lg {{ $payer['is_mine'] ? 'text-red-500' : 'text-green-400' }}">{{ $payer['is_mine'] ? '-' : '+' }}
                            ${{ $transaction->amount }}</span>

                    </div>
                    <span class="opacity-80 text-sm  text-right block">
                        {{ $transaction->description }} | {{ $transaction->created_at->format('d M Y, H:i') }}
                    </span>

                </div>
            @endforeach
        </div>
    </section>
</div>
