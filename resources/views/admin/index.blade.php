<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-bold mb-4">Nieuwe Activiteit</h3>
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