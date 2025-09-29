<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Account beheer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <form method="POST" action="{{ route('functies.update', $functie->id) }}" class="mt-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Functie Naam -->
                    <div>
                        <x-input-label for="naam" :value="__('Functie Naam')" />
                        <x-text-input 
                            id="naam" 
                            name="naam" 
                            type="text" 
                            class="mt-1 block w-full" 
                            required 
                            autofocus 
                            :value="old('naam', $functie->naam)" 
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('naam')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Opslaan') }}</x-primary-button>

                        @if (session('status') === 'functie-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600"
                            >{{ __('Functie is bijgewerkt!') }}</p>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>
    

</x-app-layout>
