<div>
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <x-form-section submit="register">
                <x-slot name="title">
                    {{ __('¡¡ Become a seller !!') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('You are one step away from becoming a seller on the best online sales platform.') }}
                </x-slot>

                <x-slot name="form">
                    <!-- Name -->
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="tenant_name" value="{{ __('Company name') }}" />
                        <x-input id="tenant_name" type="text" class="mt-1 block w-full" wire:model="tenant_name"
                            autocomplete="tenant_name" />
                        <x-input-error for="tenant_name" class="mt-2" />
                    </div>

                </x-slot>

                <x-slot name="actions">
                    <x-button wire:loading.attr="disabled">
                        {{ __('Become a seller') }}
                    </x-button>
                </x-slot>
            </x-form-section>
            <x-section-border />
        </div>
    </div>
</div>
