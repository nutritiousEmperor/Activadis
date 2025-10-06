<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Activiteit details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Titel -->
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">
                        {{ $activity->title }}
                    </h3>

                    <!-- Gegevens tabel -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-700">
                        <div>
                            <p class="font-semibold">📅 Datum:</p>
                            <p>{{ \Carbon\Carbon::parse($activity->date)->format('d-m-Y') }}</p>
                        </div>

                        <div>
                            <p class="font-semibold">⏰ Tijd:</p>
                            <p>{{ \Carbon\Carbon::parse($activity->time)->format('H:i') }}</p>
                        </div>

                        <div>
                            <p class="font-semibold">📍 Locatie:</p>
                            <p>{{ $activity->location }}</p>
                        </div>

                        <div>
                            <p class="font-semibold">👥 Max deelnemers:</p>
                            <p>{{ $activity->max_participants ?? 'Onbeperkt' }}</p>
                        </div>
                    </div>

                    <!-- Omschrijving -->
                    <div class="mt-6">
                        <p class="font-semibold">📝 Omschrijving:</p>
                        <p class="mt-2 text-gray-600 whitespace-pre-line">
                            {{ $activity->description ?? 'Geen omschrijving opgegeven.' }}
                        </p>
                    </div>

                    <!-- Knoppen -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                        <a href="{{ route('admin.activiteiten.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            Terug naar overzicht
                        </a>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 script voor verwijderen -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const deleteButtons = document.querySelectorAll(".delete-button");

            deleteButtons.forEach(button => {
                button.addEventListener("click", function () {
                    let form = this.closest("form");

                    Swal.fire({
                        title: "Weet je het zeker?",
                        text: "Deze activiteit wordt definitief verwijderd.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ja, verwijderen!",
                        cancelButtonText: "Annuleren"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
