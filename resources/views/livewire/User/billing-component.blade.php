<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Billing
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <x-form-section submit="updatePayPalInformation">
            <x-slot name="title">
                {{ __('PayPal information') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Update your PayPal\'s information') }}
            </x-slot>

            <x-slot name="form">

                <div class="col-span-6 sm:col-span-4">
                    <x-label for="paypal_email" value="{{ __('Email') }}" />
                    <x-input id="paypal_email" type="text" class="mt-1 block w-full" wire:model="paypal_email"
                        autocomplete="paypal_email" />
                    <x-input-error for="paypal_email" class="mt-2" />
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


    </div>
</div>
