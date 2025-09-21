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
            <x-primary-button>{{ __('Save') }}</x-primary-button>

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
