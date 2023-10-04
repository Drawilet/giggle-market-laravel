<div class="py-12">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Products
        </h2>
    </x-slot>

    <x-dialog-modal wire:model="saveModal">
        <x-slot name="title">
            <h1 class="text-center text-2xl">
                @if ($product_id)
                    Update product
                @else
                    Add product
                @endif
            </h1>

        </x-slot>
        <x-slot name="content">
            <div class="mx-auto h-72 w-72 bg-slate-800 rounded-full overflow-hidden relative">
                <img src="{{ gettype($photo) == 'string' ? "storage/products/$product_id/$photo" : $photo?->temporaryUrl() }}"
                    alt="" class="mx-auto overflow-hidden rounded-full max-h-72">

                <label for="photo"
                    class="w-72 h-36 bottom-0  bg-slate-900 absolute flex items-center justify-center opacity-0 hover:opacity-75 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-1/2">
                        <g data-name="Layer 2">
                            <g data-name="upload">
                                <rect width="24" height="24" transform="rotate(180 12 12)" opacity="0"
                                    fill="#303243" />
                                <rect x="4" y="4" width="16" height="2" rx="1"
                                    ry="1" transform="rotate(180 12 5)" fill="#303243" />
                                <rect x="17" y="5" width="4" height="2" rx="1"
                                    ry="1" transform="rotate(90 19 6)" fill="#303243" />
                                <rect x="3" y="5" width="4" height="2" rx="1"
                                    ry="1" transform="rotate(90 5 6)" fill="#303243" />
                                <path
                                    d="M8 14a1 1 0 0 1-.8-.4 1 1 0 0 1 .2-1.4l4-3a1 1 0 0 1 1.18 0l4 2.82a1 1 0 0 1 .24 1.39 1 1 0 0 1-1.4.24L12 11.24 8.6 13.8a1 1 0 0 1-.6.2z"
                                    fill="#303243" />
                                <path d="M12 21a1 1 0 0 1-1-1v-8a1 1 0 0 1 2 0v8a1 1 0 0 1-1 1z" fill="#303243" />
                            </g>
                        </g>
                    </svg>
                </label>

                <input wire:model="photo" type="file" id="photo" class="hidden">
            </div>

            <x-text-input wire:model="description" id="description" label="Description" />
            <x-text-input wire:model="price" id="price" type="number" label="Price ($)" />

            <div class="mb-4">
                <label for="tax_id" class="flex text-white text-sm font-bold mb-2">
                    <p class="mr-1">Taxes:</p>
                    @foreach ($taxes->whereIn('id', $taxes_id) as $tax)
                        <p class="mr-1">
                            {{ $tax->name }}
                            <sup wire:click="removeTax({{ $tax->id }})"
                                class="ml-1 text-sm text-red-500 hover:text-red-700">
                                X
                            </sup>
                        </p>
                    @endforeach
                </label>

                <select id="tax_id" wire:model="tax_id" wire:change="addTax"
                    class="block w-full mt-1 p-2 border border-gray-300 bg-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white focus:outline-none">
                    <option value="{{ null }}">Select a tax</option>
                    @foreach ($taxes as $tax)
                        @if (!in_array($tax->id, $taxes_id))
                            <option value="{{ $tax->id }}">{{ $tax->name }}</option>
                        @endif
                    @endforeach
                </select>

                @error('taxes_id')
                    <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-white text-sm font-bold mb-2">Category:</label>
                <select id="category_id" wire:model="category_id" required
                    class="block w-full mt-1 p-2 border border-gray-300 bg-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white focus:outline-none">
                    <option value="{{ null }}">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <x-text-input wire:model="stock" id="stock" type="number" label="Stock" />

            <div class=" px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click="save()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-purple-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-purple-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">Save</button>
                </span>

                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click="closeSaveModal()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">Cancel</button>
                </span>
            </div>

        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model="deleteModal">
        <x-slot name="title">

        </x-slot>
        <x-slot name="content">

            <h2 class="text-white">¿Are you sure you want do delete "{{ session('description') }}"?</h2>

            <div class=" px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click.prevent="delete()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-500 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-600 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">Delete</button>
                </span>

                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click="closeDeleteModal()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">Cancel</button>
                </span>
            </div>

        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-dialog-modal>

    <div class="max-w-7xl mx-auto sm:px6 lg:px-8">
        <div class="overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            @if (session()->has('message'))
                <div class="bg-indigo-950 rounded-b text-white px-4 py-4 shadow-md my-3" role="alert">
                    <div class="flex">
                        <div>
                            <h4>{{ session('message') }}</h4>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-gray-900 p-4">
                <h2 class="text-white text-xl font-bold">Filters:</h2>
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <label for="filter_category" class="text-white text-sm font-bold">Category:</label>
                        <select id="filter_category" wire:model="filter_category"
                            class="bg-gray-800 text-white border border-gray-700 rounded py-2 px-3 focus:outline-none focus:ring focus:border-blue-300">
                            <option value="{{ null }}">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <label for="filter_description" class="text-white text-sm font-bold">Description:</label>
                        <input type="text" id="filter_description" wire:model="filter_description"
                            class="bg-gray-800 text-white border border-gray-700 rounded py-2 px-3 focus:outline-none focus:ring focus:border-blue-300 min-w-max">

                        <label for="filter_description" class="text-white text-sm font-bold">Price:</label>
                        <input type="number" wire:model="filter_min_price" placeholder="min"
                            class="[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none bg-gray-800 text-white border border-gray-700 rounded w-14 py-2 px-3 focus:outline-none focus:ring focus:border-blue-300">
                        <p class="text-white text-sm font-bold">to</p>
                        <input type="number" wire:model="filter_max_price" placeholder="max"
                            class="[appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none bg-gray-800 text-white border border-gray-700 rounded w-14 py-2 px-3 focus:outline-none focus:ring focus:border-blue-300">

                    </div>
                    <div>
                        <button wire:click="clearFilters()"
                            class="bg-sky-500 hover:bg-sky-600 text-white font-bold py-2 px-4 rounded-sm">
                            <i class="fa-solid fa-broom mr-1"></i> Clear</button>
                        <x-is-admin>
                            <button wire:click="openSaveModal()"
                                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-sm">
                                <i class="fa-solid fa-plus mr-1"></i> Add</button>
                        </x-is-admin>
                    </div>
                </div>
            </div>

            <table class="table-fixed w-full">
                <thead>
                    <tr class="bg-slate-800 text-white">
                        <th class="px-4 py-2"></th>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2">Taxes</th>
                        <th class="px-4 py-2">Stock</th>
                        <th class="px-4 py-2">Category</th>
                        <x-is-admin>
                            <th class="px-4 py-2">Actions</th>
                        </x-is-admin>
                    </tr>
                </thead>
                <tbody>
                    @if ($products)
                        @foreach ($products as $product)
                            <tr>
                                <td class="border px-4 py-2 text-white">
                                    <img src="{{ "/storage/products/$product->id/$product->photo" }}"
                                        alt="{{ "$product->description photo" }}" class="block max-h-14 mx-auto">
                                </td>
                                <td class="border px-4 py-2 text-white">{{ $product->id }}</td>
                                <td class="border px-4 py-2 text-white">{{ $product->description }}</td>
                                <td class="border px-4 py-2 text-white">${{ $product->price }}</td>
                                <td class="border px-4 py-2 text-white">
                                    @foreach ($product->product_taxes as $product_tax)
                                        {{ $product_tax->tax->name }},
                                    @endforeach

                                </td>
                                <td class="border px-4 py-2 text-white">{{ $product->stock }}</td>
                                <td class="border px-4 py-2 text-white">{{ $product->category?->name ?? 'N/A' }}</td>


                                <x-is-admin>
                                    <td class="border px-4 py-2 text-center">
                                        <button wire:click="openUpdateModal({{ $product->id }})"
                                            class="bg-yellow-300  hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded-sm ">
                                            <i class="fa-solid fa-pencil"></i>
                                        </button>
                                        <button
                                            wire:click="openDeleteModal({{ $product->id }}, '{{ $product->description }}')"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4  rounded-sm">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
                                </x-is-admin>
                            </tr>
                        @endforeach
                    @else
                        <h1>Not found</h1>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
