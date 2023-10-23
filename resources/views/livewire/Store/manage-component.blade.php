<div>
    <x-dialog-modal wire:model="saveUserModal">
        <x-slot name="title">
            <h1 class="text-center text-2xl">
                @if ($user_id)
                    Update user
                @else
                    Add user
                @endif
            </h1>

        </x-slot>
        <x-slot name="content">
            <x-text-input wire:model="name" id="name" label="Name" />
            <x-text-input wire:model="email" id="email" type="email" label="Email" />
            <x-text-input wire:model="password" id="password" type="password" label="Password" />


            <div class="mb-4">
                <label for="store_role" class="block text-white text-sm font-bold mb-2">Role:</label>
                <select id="store_role" wire:model="store_role" required
                    class="block w-full mt-1 p-2 border border-gray-300 bg-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white focus:outline-none">
                    <option value="{{ null }}">Select a role</option>
                    <option value="seller">Seller</option>
                    <option value="admin">Admin</option>
                </select>
                @error('store_role')
                    <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class=" px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click="saveUser()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-purple-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-purple-800 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">Save</button>
                </span>

                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click="closeSaveUserModal()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">Cancel</button>
                </span>
            </div>

        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model="deleteUserModal">
        <x-slot name="title">

        </x-slot>
        <x-slot name="content">

            <h2 class="text-white">¿Are you sure you want do delete "{{ session('name') }}"?</h2>

            <div class=" px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click.prevent="deleteUser()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-red-500 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-600 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">Delete</button>
                </span>

                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click="closeDeleteUserModal()" type="button"
                        class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">Cancel</button>
                </span>
            </div>

        </x-slot>
        <x-slot name="footer"></x-slot>
    </x-dialog-modal>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $store_name }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <x-form-section submit="updateStoreInformation">
                <x-slot name="title">
                    {{ __('Store Information') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Update your stores\'s information') }}
                </x-slot>

                <x-slot name="form">
                    <!-- Name -->
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="store_name" value="{{ __('Name') }}" />
                        <x-input id="store_name" type="text" class="mt-1 block w-full" wire:model="store_name"
                            autocomplete="store_name" />
                        <x-input-error for="store_name" class="mt-2" />
                    </div>

                </x-slot>

                <x-slot name="actions">
                    <x-action-message class="mr-3" on="saved">
                        {{ __('Saved.') }}
                    </x-action-message>

                    <x-button wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </x-button>
                </x-slot>
            </x-form-section>
            <x-section-border />

            <x-form-section submit="">
                <x-slot name="title">
                    {{ __('Users') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Update your stores\'s users') }}

                    <button wire:click="openSaveUserModal()"
                        class="block rounded-sm bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 my-3">Add
                        user</button>

                </x-slot>

                <x-slot name="form">
                    <table class="table-fixed w-full col-span-6 row-span-2">
                        <thead>
                            <tr class="bg-slate-800 text-white">
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Role</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($users)
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="border px-4 py-2 text-white">{{ $user->name }}</td>
                                        <td class="border px-4 py-2 text-white">{{ $user->email }}</td>
                                        <td class="border px-4 py-2 text-white capitalize">
                                            {{ $user->store_role }}
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <button wire:click="openUpdateUserModal({{ $user->id }})"
                                                class="bg-yellow-300  hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded-sm ">
                                                <i class="fa-solid fa-pencil"></i>
                                            </button>
                                            <button
                                                wire:click="openDeleteUserModal({{ $user->id }}, '{{ $user->name }}')"
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4  rounded-sm">
                                               <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                            @endif
                        </tbody>
                    </table>

                </x-slot>

                <x-slot name="actions">

                </x-slot>
            </x-form-section>
            <x-section-border />
        </div>
    </div>
</div>
