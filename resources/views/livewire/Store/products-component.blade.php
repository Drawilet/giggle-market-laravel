<div class="py-5">
    @section('title', 'Products')

    <x-dialog-modal wire:model="modals.save">
        <x-slot name="title">
            <h1 class="text-center text-2xl">
                @if ($data['id'])
                    Update product
                @else
                    Add product
                @endif
            </h1>

        </x-slot>
        <x-slot name="content">
            <div class="mx-auto h-72 w-72 bg-base-200 rounded-full overflow-hidden relative">
                <img src="{{ gettype($data['photo']) == 'string' ? '/storage/products/' . $data['id'] . '/' . $data['photo'] : $data['photo']?->temporaryUrl() }}"
                    alt="" class="mx-auto overflow-hidden rounded-full max-h-72">

                <label for="data.photo"
                    class="w-72 h-36 bottom-0  bg-base-300 absolute flex items-center justify-center opacity-0 hover:opacity-75 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-1/2">
                        <g data-name="data.Layer 2">
                            <g data-name="data.upload">
                                <rect width="24" height="24" transform="rotate(180 12 12)" opacity="0"
                                    fill="#303243" />
                                <rect x="4" y="4" width="16" height="2" rx="1" ry="1"
                                    transform="rotate(180 12 5)" fill="#303243" />
                                <rect x="17" y="5" width="4" height="2" rx="1" ry="1"
                                    transform="rotate(90 19 6)" fill="#303243" />
                                <rect x="3" y="5" width="4" height="2" rx="1" ry="1"
                                    transform="rotate(90 5 6)" fill="#303243" />
                                <path
                                    d="M8 14a1 1 0 0 1-.8-.4 1 1 0 0 1 .2-1.4l4-3a1 1 0 0 1 1.18 0l4 2.82a1 1 0 0 1 .24 1.39 1 1 0 0 1-1.4.24L12 11.24 8.6 13.8a1 1 0 0 1-.6.2z"
                                    fill="#303243" />
                                <path d="M12 21a1 1 0 0 1-1-1v-8a1 1 0 0 1 2 0v8a1 1 0 0 1-1 1z" fill="#303243" />
                            </g>
                        </g>
                    </svg>
                </label>

                <input wire:model="data.photo" type="file" id="data.photo" class="hidden">
            </div>

            <x-text-input wire:model="data.name" id="data.name" label="Name" />
            <div class="mb-4">
                <label for="description" class="block  text-sm font-bold mb-2">Description</label>
                <textarea wire:model="data.description" name="data.description" id="data.description" rows="5"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-base-200"></textarea>

                @error('description')
                    <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <x-text-input wire:model="data.price" id="data.price" type="number" label="Price ($)" />

            <div class="mb-4">
                <label for="tax_id" class="flex  text-sm font-bold mb-2">
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

                <select id="data.tax_id" wire:model="tax_id" wire:change="addTax"
                    class="block w-full mt-1 p-2 border border-gray-300 bg-base-200 rounded-md shadow-sm ">
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
                <label for="category_id" class="block  text-sm font-bold mb-2">Category:</label>
                <select id="data.category_id" wire:model="data.category_id" required
                    class="block w-full mt-1 p-2 border border-gray-300 bg-base-200 rounded-md shadow-sm ">
                    <option value="{{ null }}">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <x-text-input wire:model="data.stock" id="data.stock" type="number" label="Stock" />
        </x-slot>
        <x-slot name="footer">

            <button wire:click="Modal('save',false)" type="button"
                class="btn btn-neutral  w-28 mr-2">Cancel</button>

            <button wire:click="save()" type="button"
                class="btn btn-accent w-28">Save</button>
        </x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model="modals.unpublish">
        <x-slot name="title">

        </x-slot>
        <x-slot name="content">

            <h2>¿Are you sure you want do unpublish "{{ $data['name'] }}"?</h2>
        </x-slot>
        <x-slot name="footer">
            <button wire:click="Modal('unpublish', false)" type="button"
                class="sm:ml-3 sm:w-auto inline-flex justify-center w-full rounded-md border border-gray-300  bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">Cancel</button>

            <button wire:click.prevent="unpublish()" type="button"
                class="sm:ml-3 sm:w-auto inline-flex justify-center w-full rounded-md border border-transparent  bg-red-500 text-base leading-6 font-medium  shadow-sm hover:bg-red-600 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">Unpublish</button>

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
                        <select id="filter.category" wire:model="filter.category"
                            class="bg-base-200 rounded py-2 px-3">
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
                        <x-store-admin>
                            <button wire:click="Modal('save', true)" class="w-2/3 bg-primary py-2 px-4 rounded-sm">
                                <i class="fa-solid fa-plus mr-1"></i> Add</button>
                        </x-store-admin>
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
                            <th class=" w-1/4">Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Taxes</th>
                            <th>Stock</th>
                            <th>Category</th>
                            @if ($filter['status'] == 'available')
                                <x-store-admin>
                                    <th>Actions</th>
                                </x-store-admin>
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

                                    @if ($product->status == 'available')
                                        <x-store-admin>
                                            <td class="border  py-2">
                                                <div class="flex justify-evenly">
                                                    <button title="Update"
                                                        wire:click="Modal('save', true, '{{ $product->id }}')"
                                                        class="bg-yellow-300  hover:bg-yellow-400  font-bold py-2 px-4 rounded-sm">
                                                        <i class="fa-solid fa-pencil"></i>
                                                    </button>
                                                    <button title="Unpublish"
                                                        wire:click="Modal('unpublish', true, '{{ $product->id }}')"
                                                        class="bg-red-500 hover:bg-red-700  font-bold py-2 px-4 rounded-sm">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </x-store-admin>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
