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
        text: @json(session('success')),
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#fbbf24',
        background: '#fff'
      }).then((result) => {
        @if(session('go_index'))
          if (result.isConfirmed) {
            window.location.href = @json(route('admin.activiteiten.index'));
          }
        @endif
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

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gasten toegestaan?</label>
                            <input type="hidden" name="gasten" value="0">
                            <input type="checkbox" name="gasten" value="1"
                            class="rounded border-gray-300 text-covadisyellow focus:ring-covadisyellow"
                            {{ old('gasten', $activity->gasten) ? 'checked' : '' }}>
                             <span class="ml-2">Ja</span>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition hover:bg-blue-700">
                            Opslaan
                        </button>
                    </form>
                    {{-- ===== Fotobeheer ===== --}}
@php
    $galleryDir = public_path('activity_photos/'.$activity->id);
    $files = is_dir($galleryDir)
        ? array_values(array_filter(scandir($galleryDir), function($f) use ($galleryDir){
            if (in_array($f, ['.','..'])) return false;
            return preg_match('/\.(jpe?g|png|webp)$/i', $f) && is_file($galleryDir.DIRECTORY_SEPARATOR.$f);
        }))
        : [];
@endphp

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
  <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
      <h3 class="text-lg font-semibold mb-3">Foto’s beheren</h3>

      {{-- Verwijderen: checkbox grid --}}
      <form method="POST" action="{{ route('admin.activiteiten.photos.delete', $activity) }}" class="mb-6">
        @csrf
        @method('DELETE')

        @if(count($files))
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($files as $f)
              <label class="relative block cursor-pointer group">
                <img src="{{ asset('activity_photos/'.$activity->id.'/'.$f) }}"
                     alt="Foto"
                     class="w-full h-32 object-cover rounded-lg border border-gray-200">
                <input type="checkbox" name="files[]" value="{{ $f }}"
                       class="absolute top-2 left-2 w-5 h-5 accent-yellow-500 bg-white rounded border border-gray-300">
                <span class="absolute bottom-2 right-2 text-[10px] bg-black/60 text-white px-2 py-0.5 rounded opacity-0 group-hover:opacity-100 transition max-w-[90%] truncate">
                  {{ Str::limit($f, 22) }}
                </span>
              </label>
            @endforeach
          </div>

          <button type="submit"
                  class="mt-4 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">
            Verwijder geselecteerde
          </button>
        @else
          <p class="text-gray-600">Nog geen foto’s geüpload.</p>
        @endif
      </form>

      {{-- Toevoegen: multi-upload --}}
      <form method="POST" action="{{ route('admin.activiteiten.photos.upload', $activity) }}" enctype="multipart/form-data">
        @csrf
        <label class="block text-sm font-medium text-gray-700 mb-1">Nieuwe foto’s toevoegen</label>
        <input type="file" name="photos[]" multiple
               accept="image/jpeg,image/png,image/webp"
               class="block w-full border-gray-300 rounded-lg shadow-sm mb-3">
        <button type="submit"
                class="bg-yellow-500 hover:bg-yellow-600 text-blue-900 font-semibold py-2 px-4 rounded-lg">
          Uploaden
        </button>
        <p class="text-xs text-gray-500 mt-1">Max 4 MB per foto. Toegestaan: JPG, PNG, WEBP.</p>
      </form>
    </div>
  </div>
</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
