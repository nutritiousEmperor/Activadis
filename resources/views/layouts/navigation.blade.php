<nav x-data="{ open: false }" class="bg-primary border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('src/covadis_logo.svg') }}" alt="Covadis Logo" class="h-9 w-auto">
                    </a>
                </div>

                <!-- Link(s) links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('activiteiten.index')" :active="request()->routeIs('activiteiten.index')" class="text-white">
                        {{ __('Activiteiten') }}
                    </x-nav-link>
                </div>

                @auth
                    @if (auth()->user()->isAdmin())
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link
                                :href="route('admin.activiteiten.index')"
                                :active="request()->routeIs('admin.activiteiten.*')"
                                class="text-white">
                                {{ __('Activiteiten beheer') }}
                            </x-nav-link>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link
                                :href="route('admin.acounts')"
                                :active="request()->routeIs(['admin.acounts','admin.registerUser'])"
                                class="text-white">
                                {{ __('Medewerkers') }}
                            </x-nav-link>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Rechterkant: user of guest -->
            @auth
                <!-- Settings Dropdown (ingelogd) -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @php
                                $uid = auth()->id();
                                $rel = $uid ? "profile_photos/pf-$uid.jpg" : null;
                                $src = ($rel && file_exists(public_path($rel)))
                                        ? asset($rel)
                                        : asset('profile_photos/default.jpg'); // zorg dat deze bestaat
                            @endphp
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-200 bg-primary hover:text-white focus:outline-none transition ease-in-out duration-150">
                                <img src="{{ $src }}" alt="Profielfoto" class="h-8 w-8 rounded-full me-2 object-cover bg-white">
                                <div class="text-white">{{ auth()->user()->name }}</div>
                                <div class="ms-1 text-white">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth

            @guest
                <!-- Guest knoppen (desktop) -->
                <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-3 py-2 rounded-md font-medium bg-main text-primary hover:brightness-95">
                        {{ __('Inloggen') }}
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center px-3 py-2 rounded-md font-medium border border-white/20 text-white hover:bg-white/10">
                            {{ __('Registreren') }}
                        </a>
                    @endif
                </div>
            @endguest

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-secondary/40 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('activiteiten.index')" :active="request()->routeIs('activiteiten.index')">
                {{ __('Activiteiten') }}
            </x-responsive-nav-link>

            @auth
                @if(auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.activiteiten.index')" :active="request()->routeIs('admin.activiteiten.*')">
                        {{ __('Activiteiten beheer') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.acounts')" :active="request()->routeIs(['admin.acounts','admin.registerUser'])">
                        {{ __('Medewerkers') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                               onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ __('Gast') }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ __('Niet ingelogd') }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Inloggen') }}
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Registreren') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
