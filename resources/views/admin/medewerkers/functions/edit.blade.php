<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Account beheer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <a href="{{ route('admin.medewerkers.functies.index') }}"
                    class="inline-flex items-center  bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    ↩ terug
                </a>
                <form method="POST" action="{{ route('admin.medewerkers.functies.update', $function->id) }}" class="mt-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="naam" :value="__('Functie Naam')" />
                        <x-text-input 
                            id="naam" 
                            name="naam" 
                            type="text" 
                            class="mt-1 block w-full" 
                            required 
                            autofocus 
                            :value="old('naam', $function->naam)" 
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
