<div class="p-5 flex flex-col lg:flex-row">
    <x-dialog-modal wire:model="transferModal">
        <x-slot name="title">
            <h1 class="text-center text-2xl">Transfer</h1>
        </x-slot>
        <x-slot name="content">
            <div class="mb-4">
                <label for="user_id" class="block  text-sm font-bold mb-2">User</label>
                <select id="user_id" wire:model="user_id"
                    class="block w-full mt-1 p-2 border border-base-300 bg-base-300 rounded-md shadow-sm">
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
                max="{{ $store->balance }}" />

            <x-text-input wire:model="description" id="description" label="Description" />


        </x-slot>
        <x-slot name="footer">
            <button wire:click="closeTransferModal()" type="button" class="btn btn-neutral w-28 mr-2">Cancel</button>
            <button wire:click="transfer()" type="button" class="btn btn-accent w-28">Transfer</button>
        </x-slot>
    </x-dialog-modal>

    <div class="w-full mr-5 mb-4 lg:w-4/6">
        <div class="bg-base-200 py-3 px-5 flex flex-col w-full rounded shadow-lg ">
            <span class="">Balance</span>
            <span class=" text-3xl">${{ $store->balance }}</span>
        </div>

        <button class="w-full btn btn-primary mt-1 py-2 rounded" wire:click="openTransferModal">
            <span class="text-base ">Transfer</span>
        </button>
    </div>

    <section class="w-full mb-4 lg:mr-5">
        <h3 class="text-lg w-full py-1 rounded bg-base-300 text-center mb-2">Sales</h3>
        <div class="flex flex-col-reverse">
            @foreach ($sales as $sale)
                @if ($sale->sale->payment_status != 'pending')
                    <div class="flex bg-base-200 p-2 mb-2 rounded flex-col">
                        <div class="flex">
                            <div class="flex p-2 sales-center flex-col w-full">
                                <p class=" text-xl"> {{ $sale->description }}</p>
                            </div>

                            <div class="flex text-right flex-col w-full">
                                <span class="text-lg ">{{ $sale->quantity }} X ${{ $sale->price }} =
                                    ${{ $sale->quantity * $sale->price }}</span>
                            </div>
                        </div>

                        <h4 class=" text-sm text-right">{{ $sale->created_at->format('d M Y, H:i') }}</h4>
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    <section class="w-full mb-4 ">
        <h3 class="text-lg w-full py-1 bg rounded bg-base-300 text-center mb-2">Transactions</h3>
        <div class="flex flex-col-reverse">
            @foreach ($transactions as $transaction)
                @php
                    $payer = $this->getPayerData($transaction, $type, $store->id);
                    $recipient = $this->getRecipientData($transaction, $type, $store->id);
                @endphp

                <div class="flex  bg-base-200 p-2 mb-2 rounded flex-col">
                    <div class="flex justify-between">
                        <div class="flex items-center mb-3">
                            <div class="flex flex-col items-center">
                                <span class="text-base ">{{ $payer['name'] }} </span>
                                <span class="text-base  -mt-2 capitalize">
                                    {{ $payer['type'] }}
                                </span>
                            </div>
                            <i class="fa-solid fa-arrow-right mx-3 "></i>

                            <div class="flex flex-col items-center">
                                <span class="text-base ">{{ $recipient['name'] }} </span>
                                <span class="text-base  -mt-2 capitalize">
                                    {{ $recipient['type'] }}
                                </span>
                            </div>

                        </div>

                        <span
                            class="mr-1  text-lg {{ $payer['is_mine'] ? 'text-red-500' : 'text-green-500' }}">{{ $payer['is_mine'] ? '-' : '+' }}
                            ${{ $transaction->amount }}</span>

                    </div>
                    <span class=" text-sm  text-right block">
                        {{ $transaction->description }} | {{ $transaction->created_at->format('d M Y, H:i') }}
                    </span>

                </div>
            @endforeach
        </div>
    </section>
</div>
