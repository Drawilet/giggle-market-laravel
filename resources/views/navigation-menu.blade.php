<nav x-data="{ open: false }" class="bg-base-200 sticky top-0 border-b border-b-base-300">
    @php
        $user = Auth::user();

        $navigation = [
            'user.dashboard' => [
                'label' => 'Dashboard',
            ],
            'catalog' => [
                'label' => 'Catalog',
            ],
            'store.new' => [
                'label' => '¡Become a seller!',
                'middleware' => !$user->store,
            ],
        ];

        $menu = [
            'Manage Account' => [
                'profile.show' => 'Profile',
                'user.billing' => 'Billing',
                'user.purchases' => 'Purchases',
            ],
            'Store' => [
                'middleware' => $user->store_role == 'seller',
                'store.products' => 'Products',
            ],
            'Store' => [
                'middleware' => $user->store_role == 'admin',
                'store.manage' => 'Manage',
                'store.dashboard' => 'Dashboard',
                'store.products' => 'Products',
            ],

            'App' => [
                'middleware' => $user->role == 'admin',
                'app.manage' => 'Manage',
                'app.categories' => 'Categories',
                'app.taxes' => 'Taxes',
                'moderator.dashboard' => 'Moderator',
            ],
        ];

    @endphp

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex ">
                <div class="drawer ">
                    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
                    <div class="drawer-content flex items-center justify-center">
                        <label for="my-drawer" class="drawer-button"><i class="fa-solid fa-bars text-xl"></i></label>
                    </div>
                    <div class="drawer-side ">
                        <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
                        <ul class="menu p-4 w-80 min-h-full bg-base-200 text-base-content">

                            @foreach ($menu as $key => $links)
                                @if (!isset($links['middleware']) || (isset($links['middleware']) && $links['middleware'] !== false))
                                    <div class="block px-4 py-2 text-xs text-accent">
                                        {{ $key }}
                                    </div>
                                    @foreach ($links as $link => $label)
                                        @if ($link != 'middleware')
                                            <x-dropdown-link href="{{ route($link) }}">
                                                {{ $label }}
                                            </x-dropdown-link>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                        </ul>
                    </div>
                </div>

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="mx-auto">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @foreach ($navigation as $key => $link)
                        @if (!isset($link['middleware']) || (isset($link['middleware']) && $link['middleware'] !== false))
                            <x-nav-link href="{{ route($key) }}" :active="request()->routeIs($key)">
                                {{ $link['label'] }}
                            </x-nav-link>
                        @endif
                    @endforeach
                </div>
            </div>

            @if (!request()->routeIs('checkout'))
                @livewire('cart-component')
            @endif

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="ml-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover"
                                        src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md">
                                        <p>
                                            <span class="block">
                                                {{ Auth::user()->name }}
                                            </span>

                                            <span class="text-xs">
                                                {{ Auth::user()->store?->name }}
                                            </span>

                                        </p>

                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>
</nav>
