<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Activiteit toevoegen') }}
        </h2>
    </x-slot>

    <!-- Succes melding -->
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Succes!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#fbbf24',
                    background: '#fff'
                });
            });
        </script>
    @endif

    <!-- Error meldingen -->
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
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
                    <form method="POST" action="{{ route('admin.activiteiten.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Titel</label>
                            <input type="text" name="title"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Omschrijving</label>
                            <textarea name="description" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Datum</label>
                            <input type="date" name="date"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tijd</label>
                            <input type="time" name="time"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Locatie</label>
                            <input type="text" name="location"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow"
                                required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Max deelnemers</label>
                            <input type="number" name="max_participants" min="1"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow"
                                required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gasten toegestaan?</label>
                            <input type="checkbox" name="gasten" value="1"
                                class="rounded border-gray-300 text-covadisyellow focus:ring-covadisyellow">
                            <span class="ml-2">Ja</span>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Foto's</label>
                            <input type="file" name="activity_photos[]" multiple
                                accept="image/jpeg,image/png,image/webp"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow">
                            <p class="text-sm text-gray-500 mt-1">Je kunt meerdere afbeeldingen selecteren.</p>
                        </div>

                        <button type="submit"
                            class="w-full bg-covadisyellow bg-yellow-500 text-covadisblue font-semibold py-2 px-4 rounded-lg shadow-md transition">
                            Opslaan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>