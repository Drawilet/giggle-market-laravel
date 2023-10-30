<div class="py-5">
    <x-dialog-modal wire:model="modals.save">
        <x-slot name="title">

        </x-slot>
        <x-slot name="content">
            <form wire:submit.prevent="save">
                @foreach ($initialData as $key => $value)
                    @if ($key != 'id')
                        <x-text-input id="{{ $key }}" wire:model="data.{{ $key }}"
                            label="{{ ucfirst($key) }}" type="{{ gettype($value) == 'integer' ? 'number' : 'string' }}" />
                    @endif
                @endforeach
            </form>
        </x-slot>
        <x-slot name="footer">
            <button wire:click="Modal('save', false)" type="button" class="btn btn-neutral w-28 mr-2">Cancel</button>
            <button class=" btn btn-accent w-28">Save</button>
        </x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model="modals.delete">
        <x-slot name="title">

        </x-slot>
        <x-slot name="content">

            <h2>¿Are you sure you want do delete "{{ $data['name'] }}" {{ $name }}?</h2>
        </x-slot>
        <x-slot name="footer">
            <button wire:click="Modal('delete', false)" type="button" class="btn btn-neutral w-28">Cancel</button>
            <button wire:click.prevent="delete()" type="button" class="btn btn-warning w-28 mr-2">Delete</button>

        </x-slot>
    </x-dialog-modal>

    <x-dialog-modal wire:model="modals.error">
        <x-slot name="title">
            Unable to delete this {{ $name }}.
        </x-slot>
        <x-slot name="content">
            <h2>There are {{ $count }} product(s) linked to this {{ $name }}.</h2>
        </x-slot>
        <x-slot name="footer">
            <button wire:click="Modal('error', false)" type="button" class="btn btn-accent w-28">Close</button>


        </x-slot>
    </x-dialog-modal>

    <div class="max-w-7xl mx-auto sm:px6 lg:px-8">
        <div class=" overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
            @if (session()->has('message'))
                <div class="bg-base-200 rounded-b  px-4 py-4 shadow-md my-3" role="alert">
                    <div class="flex">
                        <div>
                            <h4>{{ session('message') }}</h4>
                        </div>
                    </div>
                </div>
            @endif

            <button wire:click="Modal('save', true)" class="rounded-sm bg-primary font-bold py-2 px-4 my-3">
                <i class="fa-solid fa-plus mr-1"></i> Add</button>


            <table class="table-fixed w-full">

                <thead>
                    <tr>
                        <th>ID</th>
                        @foreach ($keys as $key)
                            <th class="capitalize">{{ $key }}</th>
                        @endforeach
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td class="  ">{{ $item->id }}</td>
                            @foreach ($keys as $key)
                                <td class="  ">{{ $item[$key] }}</td>
                            @endforeach

                            <td>
                                <button wire:click="Modal('save', true, '{{ $item->id }}')"
                                    class="bg-warning  font-bold py-2 px-4 rounded-sm ">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                <button wire:click="Modal('delete', true, '{{ $item->id }}')"
                                    class="bg-error  font-bold py-2 px-4  rounded-sm">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script type="module">
        Echo.channel("global").listen('{{ $Name }}Event', (e) => {
            window.livewire.emit('socket', e)
        })
    </script>
</div>
