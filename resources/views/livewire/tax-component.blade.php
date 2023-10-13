<div class="py-5">
    <x-dialog-modal wire:model="saveModal">
        <x-slot name="title">

        </x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="save">
                <x-text-input id="name" wire:model="name" label="Name" />
                <x-text-input id="percentage" wire:model="percentage" type="number" label="Percentage (%)" />

                <div class=" px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button
                            class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-purple-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-purple-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">Save</button>
                    </span>

                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click="closeSaveModal()" type="button"
                            class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">Cancel</button>
                    </span>
                </div>

            </form>

        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model="deleteModal">
        <x-slot name="title">

        </x-slot>
        <x-slot name="content">

            <h2 class="text-white">¿Are you sure you want do delete "{{ session('name') }}" tax?</h2>

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
        <div class=" overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            @if (session()->has('message'))
                <div class="bg-indigo-950 rounded-b text-white px-4 py-4 shadow-md my-3" role="alert">
                    <div class="flex">
                        <div>
                            <h4>{{ session('message') }}</h4>
                        </div>
                    </div>
                </div>
            @endif

            <x-is-admin>
                <button wire:click="openSaveModal()"
                    class="rounded-sm bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 my-3">
                    <i class="fa-solid fa-plus mr-1"></i> Add
                </button>
            </x-is-admin>

            <table class="table-fixed w-full">
                <thead>
                    <tr class="bg-slate-800 text-white">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Percentage</th>
                        <x-is-admin>
                            <th class="px-4 py-2">Actions</th>
                        </x-is-admin>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($taxes as $tax)
                        <tr>
                            <td class="border px-4 py-2 text-white">{{ $tax->id }}</td>
                            <td class="border px-4 py-2 text-white">{{ $tax->name }}</td>
                            <td class="border px-4 py-2 text-white">{{ $tax->percentage }}%</td>

                            <x-is-admin>
                                <td class="border px-4 py-2 text-center">
                                    <button wire:click="openUpdateModal({{ $tax->id }})"
                                        class="bg-yellow-300  hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded-sm ">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                    <button wire:click="openDeleteModal({{ $tax->id }}, '{{ $tax->name }}')"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4  rounded-sm">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </x-is-admin>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
