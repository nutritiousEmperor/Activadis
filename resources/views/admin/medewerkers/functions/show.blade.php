<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Functie Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                
                <a href="{{ route('admin.medewerkers.functies.index') }}"
                    class="inline-flex items-center  bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    ↩ terug
                </a>

                <div class="mt-6 space-y-6">

                    <div>
                        <x-input-label :value="__('Functie Naam')" />
                        <p class="mt-1 text-gray-900 text-lg">{{ $function->naam }}</p>
                    </div>

                    <div>
                        <x-input-label :value="__('Aantal medewerkers')" />
                        <p class="mt-1 text-gray-900">{{ $function->medewerkers_count ?? '0' }}</p>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
