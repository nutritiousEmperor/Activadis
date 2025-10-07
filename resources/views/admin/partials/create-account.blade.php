<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Account registratie') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Maak een account aan voor een medewerker. Die krijgt vervolgens een e-mail om een wachtwoord aan te maken.") }}
        </p>
    </header>


    <form method="post" action="{{ route('registerAccount.send') }}" class="mt-6 space-y-6">
        @csrf


        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" autocomplete="functie"/>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" autocomplete="functie"/>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

           
        </div>

        <!--
        <div>
            <x-input-label for="functions" :value="__('Functies')" />

            <div x-data="{ 
                open: false, 
                selected: @js(old('functions', $userFunctions ?? [])), 
                options: @js($functions) 
            }" class="relative w-full">
                <button type="button" @click="open = !open"
                    class="w-full flex justify-between items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:outline-none">
                    <span 
                        x-text="selected.length > 0 
                            ? selected.map(id => options.find(o => o.id == id)?.naam).join(', ') 
                            : 'Kies opties'">
                    </span>
                    <svg class="w-4 h-4 ml-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false"
                    class="absolute mt-1 w-full rounded-lg bg-white shadow-lg border border-gray-200 z-10">
                    <ul class="max-h-60 overflow-y-auto p-2 text-sm">
                        <template x-for="option in options" :key="option.id">
                            <li>
                                <label class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded cursor-pointer">
                                    <input type="checkbox" :value="option.id" x-model="selected"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="option.naam"></span>
                                </label>
                            </li>
                        </template>
                    </ul>
                </div>

                <template x-for="value in selected" :key="value">
                    <input type="hidden" name="functions[]" :value="value">
                </template>
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('functions')" />
        </div> -->

        <div>
            <x-input-label for="function" :value="__('Functie')" />

            <div x-data="{ 
                open: false, 
                selected: @js(old('function', $userFunctions ?? null)), 
                options: @js($functions) 
            }" class="relative w-full">

                <button type="button" @click="open = !open"
                    class="w-full flex justify-between items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:outline-none">
                    <span 
                        x-text="selected 
                            ? (options.find(o => o.id == selected)?.naam ?? 'Kies optie') 
                            : 'Kies optie'">
                    </span>
                    <svg class="w-4 h-4 ml-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false"
                    class="absolute mt-1 w-full rounded-lg bg-white shadow-lg border border-gray-200 z-10">
                    <ul class="max-h-60 overflow-y-auto p-2 text-sm">
                        <template x-for="option in options" :key="option.id">
                            <li>
                                <button type="button" 
                                    @click="selected = option.id; open = false"
                                    class="w-full text-left p-2 hover:bg-gray-100 rounded">
                                    <span x-text="option.naam"></span>
                                </button>
                            </li>
                        </template>
                    </ul>
                </div>

                <!-- ✅ Gebruik :value in plaats van x-model -->
                <input type="hidden" name="function" :value="selected">
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('function')" />
        </div>



        <div>
            <x-input-label for="role" :value="__('Rol voor account')" />

            <input type="radio" name="role" id="rol-user" value="user">
            <label for="rol-user">User</label> <br>

            <input type="radio" name="role" id="rol-admin" value="admin">
            <label for="rol-admin">Admin</label>

            <x-input-error class="mt-2" :messages="$errors->get('role')" />
        </div>



        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Maak account') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Account aangemaakt.') }}</p>
            @endif
        </div>
    </form>
</section>
