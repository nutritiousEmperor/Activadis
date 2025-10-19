<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Account beheer') }}
        </h2>
    </x-slot>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="p-6 text-gray-900">
                    <a href="{{ route('admin.medewerkers.functies.index') }}"
                        class="inline-flex items-center  bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-undo2-icon lucide-undo-2"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11"/></svg> Terug
                    </a>
                    <form method="POST" action="{{ route('admin.medewerkers.functies.store') }}" class="mt-6 space-y-6">
                        @csrf

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
                                :value="old('naam')" 
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('naam')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Opslaan') }}</x-primary-button>

                            @if (session('status') === 'functie-created')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600"
                                >{{ __('Functie is aangemaakt!') }}</p>
                                
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    

</x-app-layout>
