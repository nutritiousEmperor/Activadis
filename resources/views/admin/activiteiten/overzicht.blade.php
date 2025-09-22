


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
                    <div
                        class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between mb-6">
                        <a href="{{ route('admin.registerUser') }}"
                            class="inline-flex items-center  bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            + Nieuwe Activiteit
                        </a>
                    </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                <tr class="text-left text-gray-500">
                                    <th>Naam</th>
                                    <th>Email</th>
                                    <th>functie</th>
                                    <th>Role</th>
                                    <th>Acties</th>
                                </tr>
                                </thead>
                                <tbody>
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
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center text-gray-500">Er zijn nog geen activiteiten toegevoegd.</p>
                            @endif
                                </tbody>
                            </table>
                         </div>
                </div>
            </div>
        </div>
    </div>
            
</x-app-layout>
