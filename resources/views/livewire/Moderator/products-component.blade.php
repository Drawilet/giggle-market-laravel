<div class="py-5">
    @section('title', 'Moderate products')

    <x-dialog-modal wire:model="modals.approve">
        <x-slot name="title">
            <h3 class="font-bold text-lg">Approve product</h3>
        </x-slot>
        <x-slot name="content">
            <p class="py-4">Are you sure you want to approve {{ $data['name'] }}?</p>
        </x-slot>
        <x-slot name="footer">
            <button class="btn mr-2" wire:click='Modal("approve", false)'>Close</button>
            <button class="btn btn-primary" wire:click='approve()'>Approve</button>
        </x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model="modals.decline">
        <x-slot name="title">
            <h3 class="font-bold text-lg">Decline product</h3>
        </x-slot>
        <x-slot name="content">
            <p class="py-4">Are you sure you want to decline {{ $data['name'] }}?</p>
        </x-slot>
        <x-slot name="footer">
            <button class="btn mr-2" wire:click='Modal("decline", false)'>Close</button>
            <button class="btn btn-primary" wire:click='decline()'>Decline</button>
        </x-slot>
    </x-dialog-modal>

    <div class="max-w-7xl mx-auto">
        <div class="overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            @if (session()->has('message'))
                <div class="rounded-b  px-4 py-4 shadow-md my-3" role="alert">
                    <div class="flex">
                        <div>
                            <h4>{{ session('message') }}</h4>
                        </div>
                    </div>
                </div>
            @endif

            <div>
                <h2 class=" text-xl ">Filters:</h2>
                <div class="flex items-center flex-wrap">
                    <div class="flex items-center mb-2 mr-4 lg:mb-0">
                        <label for="filter.category" class=" text-sm  mr-4">Category:</label>
                        <select id="filter.category" wire:model="filter.category" class="bg-base-200 rounded py-2 px-3">
                            <option value="{{ null }}">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center mb-2 mr-4 lg:mb-0">
                        <label for="filter.name" class=" text-sm   mr-4">Name:</label>
                        <input type="text" id="filter.name" wire:model="filter.name"
                            class="bg-base-200  rounded py-2 px-3 min-w-max">
                    </div>
                    <div class="flex items-center mr-4">
                        <label for="filter.min_price" class=" text-sm   mr-4">Price:</label>
                        <input type="number" id="filter.min_price" wire:model="filter.min_price" placeholder="min"
                            class="[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none bg-base-200 rounded w-14 py-2 px-3">
                        <p class=" text-sm  mx-1">to</p>
                        <input type="number" wire:model="filter.max_price" placeholder="max"
                            class="[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none bg-base-200 rounded w-14 py-2 px-3">
                    </div>

                    <div class="flex w-full mt-2 lg:w-1/3 lg:mt-0">
                        <button wire:click="clearFilters()" class="w-2/3 bg-secondary py-2 px-4 rounded-sm">
                            <i class="fa-solid fa-broom mr-1"></i> Clear</button>
                    </div>

                    <div class="flex w-full mt-2 lg:w-1/3">
                        @foreach ($statusType as $key => $data)
                            <button wire:click="changeStatus('{{ $key }}')"
                                class="w-2/3 {{ $filter['status'] == $key ? 'bg-base-200' : 'bg-base-100' }} hover:bg-base-300  p-1 text-center">
                                <i class="{{ $data['icon'] }} text-sm" style="color: {{ $data['color'] }}"></i>
                                {{ $data['label'] }}

                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-base-100 ">
                            <th>ID</th>
                            <th>Image</th>
                            <th class="w-1/4">Name</th>
                            <th class="w-1/6">Description</th>
                            <th>Price</th>
                            <th>Taxes</th>
                            <th>Stock</th>
                            <th>Category</th>
                            @if ($filter['status'] != 'unavailable')
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($products)
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        <img src="{{ "/storage/products/$product->id/$product->photo" }}"
                                            alt="{{ "$product->description photo" }}" class="max-h-14">
                                    </td>
                                    <td><a href="/products/{{ $product->id }}">{{ $product->name }}</a>
                                        <span class="text-sm opacity-75 block">{{ $product->user->name }}</span>
                                    </td>
                                    <td class=" truncate">{{ $product->description }}</td>
                                    <td>${{ $product->price }}</td>
                                    <td>
                                        @foreach ($product->product_taxes as $product_tax)
                                            {{ $product_tax->tax->name }},
                                        @endforeach
                                    </td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        {{ $product->category ? $product->category->name : 'N/A' }}</td>

                                    <td class="border  py-2">
                                        <div class="flex justify-evenly">
                                            <button title="Approve"
                                                wire:click='Modal("approve",true, "{{ $product->id }}")'
                                                class="bg-green-300  hover:bg-green-400  text-white font-bold py-2 px-4 rounded-sm">
                                                <i class="fa-solid fa-check"></i>
                                            </button>

                                            <button title="Decline"
                                                wire:click='Modal("decline",true, "{{ $product->id }}")'
                                                class="bg-red-300  hover:bg-red-400  text-white font-bold py-2 px-4 rounded-sm">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>



</div>
