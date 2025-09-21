<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Activiteit aanmaken') }}
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.activities.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Titel</label>
                        <input type="text" name="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Omschrijving</label>
                        <textarea name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Datum</label>
                        <input type="date" name="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tijd</label>
                        <input type="time" name="time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('time') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Locatie</label>
                        <input type="text" name="location" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('location') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Max deelnemers</label>
                        <input type="number" name="max_participants" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required min="1">
                        @error('max_participants') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <x-primary-button type="submit">Opslaan</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
