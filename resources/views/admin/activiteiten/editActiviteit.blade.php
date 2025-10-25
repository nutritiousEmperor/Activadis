<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl leading-tight">
      {{ __('Activiteit bewerken') }}
    </h2>
  </x-slot>

  <!-- Succes melding -->
  @if(session('success'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
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
          <form method="POST" action="{{ route('admin.activiteiten.update', $activity->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Titel</label>
              <input type="text" name="title" value="{{ old('title', $activity->title) }}"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow"
                required>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Omschrijving</label>
              <textarea name="description" rows="3"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow">{{ old('description', $activity->description) }}</textarea>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Datum</label>
              <input type="date" name="date" value="{{ old('date', $activity->date) }}"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow"
                required>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Tijd</label>
              <input type="time" name="time" value="{{ old('time', $activity->time) }}"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow"
                required>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700">Locatie</label>
              <input type="text" name="location" value="{{ old('location', $activity->location) }}"
                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow"
                required>
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
                class="rounded border-gray-300 text-covadisyellow focus:ring-covadisyellow" {{ old('gasten', $activity->gasten) ? 'checked' : '' }}>
              <span class="ml-2">Ja</span>
            </div>

            <button type="submit"
              class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition hover:bg-blue-700">
              Opslaan
            </button>
          </form>
          {{-- Fotobeheer --}}


          {{-- ===== Fotobeheer (upload via drop, sorteer autosave, verwijderen) ===== --}}
          @php
            $galleryDir = public_path('activity_photos/' . $activity->id);
            $files = is_dir($galleryDir)
              ? array_values(array_filter(scandir($galleryDir) ?: [], function ($f) use ($galleryDir) {
                if (in_array($f, ['.', '..', '.order.json']))
                  return false;
                return preg_match('/\.(jpe?g|png|webp|gif)$/i', $f) && is_file($galleryDir . DIRECTORY_SEPARATOR . $f);
              }))
              : [];

            // volgorde uit .order.json toepassen
            $orderPath = $galleryDir . DIRECTORY_SEPARATOR . '.order.json';
            $ordered = $files;
            if (is_file($orderPath)) {
              $json = json_decode(@file_get_contents($orderPath), true) ?: [];
              $inOrder = array_values(array_filter($json, fn($n) => in_array($n, $files, true)));
              $remaining = array_values(array_diff($files, $inOrder));
              sort($remaining, SORT_NATURAL | SORT_FLAG_CASE);
              $ordered = array_values(array_merge($inOrder, $remaining));
            } else {
              sort($ordered, SORT_NATURAL | SORT_FLAG_CASE);
            }
          @endphp

          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 text-gray-900">
                <h3 class="text-lg font-semibold mb-3">Foto’s beheren</h3>

                {{-- Dropzone (klik of drop uit Verkenner) --}}
                <div id="dropzone"
                  class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center mb-4 transition">
                  <p class="text-sm text-gray-600">Sleep bestanden hierheen of klik om te kiezen.</p>
                  <input id="file-input" type="file" multiple accept="image/jpeg,image/png,image/webp,image/gif"
                    class="hidden">
                  <p class="text-sm text-gray-600">Max 4 MB per foto. Toegestaan: JPG, PNG, WEBP</p>
                  <div id="dz-progress" class="mt-2 text-xs text-gray-500 hidden">Bezig met uploaden…</div>
                </div>

                {{-- Verwijderen: eigen form (NIET nesten in je update-form) --}}
                <form id="delete-form" method="POST" action="{{ route('admin.activiteiten.photos.delete', $activity) }}"
                  class="mb-6">
                  @csrf
                  @method('DELETE')

                  <ul id="photo-grid"
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 select-none">
                    @forelse($ordered as $f)
                      <li class="card relative border border-gray-200 rounded-lg p-2 bg-white" data-name="{{ $f }}">
                        <input type="checkbox" name="files[]" value="{{ $f }}"
                          class="absolute top-2 left-2 w-5 h-5 accent-yellow-500 bg-white rounded border border-gray-300">
                        <div class="aspect-square overflow-hidden rounded-md border border-gray-100">
                          <img src="{{ asset('activity_photos/' . $activity->id . '/' . $f) }}" alt="{{ $f }}"
                            class="w-full h-full object-cover pointer-events-none">
                        </div>
                        <div class="flex items-center justify-between mt-2">
                          <span class="text-[11px] text-gray-600 truncate max-w-[70%]" title="{{ $f }}">
                            {{ \Illuminate\Support\Str::limit($f, 26) }}
                          </span>
                          <span class="handle cursor-grab text-gray-700 text-sm" title="Slepen">☰</span>
                        </div>
                      </li>
                    @empty
                      <li class="text-gray-600">Nog geen foto’s.</li>
                    @endforelse
                  </ul>

                  <button type="submit"
                    class="mt-4 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">
                    Verwijder geselecteerde
                  </button>
                </form>

                <div id="order-toast" class="mt-2 text-sm hidden"></div>
              </div>
            </div>
          </div>

          {{-- Sortable + logica --}}
          <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js" defer></script>
          <script>
            document.addEventListener('DOMContentLoaded', function () {
              const grid = document.getElementById('photo-grid');
              const toast = document.getElementById('order-toast');
              const dropzone = document.getElementById('dropzone');
              const fileInput = document.getElementById('file-input');
              const dzProgress = document.getElementById('dz-progress');

              if (!grid) return;

              // Autosave volgorde (debounced)
              let saveTimer = null;
              const autosave = () => {
                clearTimeout(saveTimer);
                saveTimer = setTimeout(async () => {
                  const names = Array.from(grid.querySelectorAll('.card')).map(li => li.dataset.name);
                  try {
                    const res = await fetch(@json(route('admin.activiteiten.photos.order', $activity)), {
                      method: 'POST',
                      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': @json(csrf_token()) },
                      body: JSON.stringify({ order: names })
                    });
                    toast.classList.remove('hidden');
                    if (!res.ok) {
                      toast.className = 'mt-2 text-sm text-red-700';
                      toast.textContent = 'Opslaan mislukt (' + res.status + ').';
                      return;
                    }
                    toast.className = 'mt-2 text-sm text-green-700';
                    toast.textContent = 'Volgorde opgeslagen.';
                  } catch {
                    toast.classList.remove('hidden');
                    toast.className = 'mt-2 text-sm text-red-700';
                    toast.textContent = 'Netwerkfout tijdens opslaan.';
                  }
                }, 300);
              };

              new Sortable(grid, {
                animation: 150,
                handle: '.handle',
                ghostClass: 'opacity-50',
                onEnd: autosave
              });

              // Dropzone
              dropzone.addEventListener('click', () => fileInput.click());
              ['dragenter', 'dragover'].forEach(ev => dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.add('border-yellow-400', 'bg-yellow-50'); }));
              ['dragleave', 'drop'].forEach(ev => dropzone.addEventListener(ev, e => { e.preventDefault(); dropzone.classList.remove('border-yellow-400', 'bg-yellow-50'); }));

              dropzone.addEventListener('drop', e => handleFiles(e.dataTransfer.files));
              fileInput.addEventListener('change', e => handleFiles(e.target.files));

              async function handleFiles(fileList) {
                if (!fileList || !fileList.length) return;
                dzProgress.classList.remove('hidden');
                dzProgress.textContent = 'Bezig met uploaden…';

                const form = new FormData();
                Array.from(fileList).forEach(f => form.append('photos[]', f));
                try {
                  const res = await fetch(@json(route('admin.activiteiten.photos.upload', $activity)), {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': @json(csrf_token()) },
                    body: form
                  });
                  if (!res.ok) {
                    dzProgress.className = 'mt-2 text-xs text-red-700';
                    dzProgress.textContent = 'Upload mislukt (' + res.status + ').';
                    return;
                  }
                  location.reload();
                } catch {
                  dzProgress.className = 'mt-2 text-xs text-red-700';
                  dzProgress.textContent = 'Netwerkfout tijdens upload.';
                }
              }
            });
          </script>

          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
          <script>
            document.addEventListener('DOMContentLoaded', function () {
              const deleteForm = document.getElementById('delete-form');
              if (!deleteForm) return;

              deleteForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const checked = deleteForm.querySelectorAll('input[name="files[]"]:checked');
                if (checked.length === 0) {
                  Swal.fire({
                    icon: 'info',
                    title: 'Geen foto geselecteerd',
                    text: 'Selecteer eerst één of meer foto’s om te verwijderen.',
                    confirmButtonColor: '#fbbf24'
                  });
                  return;
                }

                Swal.fire({
                  title: 'Weet je het zeker?',
                  text: 'De geselecteerde foto’s worden permanent verwijderd.',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#e11d48',
                  cancelButtonColor: '#6b7280',
                  confirmButtonText: 'Ja, verwijderen',
                  cancelButtonText: 'Annuleren',
                  background: '#fff'
                }).then((result) => {
                  if (result.isConfirmed) {
                    deleteForm.submit();
                  }
                });
              });
            });
          </script>



        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
  </div>
</x-app-layout>