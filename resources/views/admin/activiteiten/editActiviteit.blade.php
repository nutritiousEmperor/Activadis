<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Activiteit bewerken') }}
        </h2>
    </x-slot>

    <!-- Succes melding -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Succes!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#fbbf24',
                    background: '#fff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('admin.activiteiten.index') }}";
                    }
                });
            });
        </script>
    @endif

    <!-- Error meldingen -->
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Oeps!',
                    html: `
                        <ul style="text-align: left;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    `,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#fbbf24',
                });
            });
        </script>
    @endif

    <!-- Formulier -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.activiteiten.update', $activity->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Titel</label>
                            <input type="text" name="title" 
                                value="{{ old('title', $activity->title) }}" 
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Omschrijving</label>
                            <textarea name="description" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow">{{ old('description', $activity->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Datum</label>
                            <input type="date" name="date"
                                value="{{ old('date', $activity->date) }}"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tijd</label>
                            <input type="time" name="time"
                                value="{{ old('time', $activity->time) }}"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Locatie</label>
                            <input type="text" name="location"
                                value="{{ old('location', $activity->location) }}"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow" required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Max deelnemers</label>
                            <input type="number" name="max_participants" min="1"
                                value="{{ old('max_participants', $activity->max_participants) }}"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition hover:bg-blue-700">
                            Opslaan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
