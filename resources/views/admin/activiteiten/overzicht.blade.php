<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Activiteiten beheer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between mb-6">
                        <a href="{{ route('admin.activiteiten.create') }}"
                            class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            + Nieuwe Activiteit
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        @if($activities->count() > 0)
                            <div class="space-y-6">
                                @foreach($activities as $activity)
                                    <div class="bg-white rounded-2xl shadow-2xl p-6">
                                        <h2 class="text-2xl font-semibold text-covadisblue mb-3">{{ $activity->title }}</h2>
                                        
                                        <div class="text-gray-700 space-y-1">
                                            <p><strong>Omschrijving:</strong> {{ $activity->description ?? 'Geen omschrijving' }}</p>
                                            <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($activity->date)->format('d-m-Y') }}</p>
                                            <p><strong>Tijd:</strong> {{ \Carbon\Carbon::parse($activity->time)->format('H:i') }}</p>
                                            <p><strong>Locatie:</strong> {{ $activity->location }}</p>
                                            <p><strong>Max deelnemers:</strong> {{ $activity->max_participants ?? 'Onbeperkt' }}</p>
                                        </div>

                                        <!-- Actie knoppen -->
                                        <div class="mt-4 flex gap-2">
                                            <!-- Bewerken knop -->
                                            <a href="{{ route('admin.activiteiten.edit', $activity->id) }}" 
                                               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                Bewerken
                                            </a>

                                            <!-- Verwijderen knop -->
                                            <form action="{{ route('admin.activiteiten.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze activiteit wilt verwijderen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                                    Verwijderen
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-500">Er zijn nog geen activiteiten toegevoegd.</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
