<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Account registratie') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update de accounts gegevens van een medewerker") }}
        </p>
    </header>


    <form method="post" action="{{ route('registerAccount.update', $user->id) }}" class="mt-6 space-y-6">
        @csrf


        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" autocomplete="functie" :value="old('name', $user->name)"/>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required autocomplete="username" autocomplete="functie" :value="old('email', $user->email)"/>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

           
        </div>

        <div>
            <x-input-label for="functie" :value="__('Functie')" />
            <x-text-input id="functie" name="functie" type="text" class="mt-1 block w-full" required autofocus autocomplete="functie" :value="old('functie', $user->functie)" />
            <x-input-error class="mt-2" :messages="$errors->get('functie')" />
        </div>

        <div>
            <x-input-label for="permissions" :value="__('Functie')" />

            <div x-data="{ open: false, selected: [] }" class="relative w-full">
                <button type="button" @click="open = !open"
                    class="w-full flex justify-between items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:outline-none">
                    <span x-text="selected.length > 0 ? selected.join(', ') : 'Kies opties'"></span>
                    <svg class="w-4 h-4 ml-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false"
                    class="absolute mt-1 w-full rounded-lg bg-white shadow-lg border border-gray-200 z-10">
                    <ul class="max-h-60 overflow-y-auto p-2 text-sm">
                        <template x-for="option in ['Optie 1', 'Optie 2', 'Optie 3', 'Optie 4']" :key="option">
                            <li>
                                <label class="flex items-center space-x-2 p-2 hover:bg-gray-100 rounded cursor-pointer">
                                    <input type="checkbox" :value="option" x-model="selected"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span x-text="option"></span>
                                </label>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>



        <div>
            <x-input-label for="role" :value="__('Rol voor account')" />

            <input type="radio" name="role" id="rol-user" value="user"
                {{ old('role', $user->role) === 'user' ? 'checked' : '' }}>
            <label for="rol-user">User</label> <br>

            <input type="radio" name="role" id="rol-admin" value="admin"
                {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}>
            <label for="rol-admin">Admin</label>

            <x-input-error class="mt-2" :messages="$errors->get('role')" />
        </div>



        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Opslaan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Updates opgeslagen!.') }}</p>
            @endif
        </div>
    </form>
</section>
