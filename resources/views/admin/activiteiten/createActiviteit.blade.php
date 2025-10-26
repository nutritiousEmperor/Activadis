<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Activiteit toevoegen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Terug knop -->
                    <a href="{{ route('admin.activiteiten.index') }}"
                        class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-undo2-icon lucide-undo-2"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11"/></svg>
                        Terug
                    </a>


                    <!-- Formulier -->
                    <form method="POST" action="{{ route('admin.activiteiten.store') }}" enctype="multipart/form-data" class="mt-6 space-y-3">
                        @csrf

                        <!-- Titel -->
                        <div>
                            <x-input-label for="title" :value="__('Titel')" />
                            <x-text-input 
                                id="title" 
                                name="title" 
                                type="text" 
                                class="mt-1 block w-full" 
                                required 
                                autofocus 
                                :value="old('title')" 
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <!-- Omschrijving -->
                        <div>
                            <x-input-label for="description" :value="__('Omschrijving')" />
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="3"
                                maxlength="300"
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow"
                                oninput="updateCharCount()"
                                placeholder="Beschrijf de activiteit..."
                            >{{ old('description') }}</textarea>
                            <div class="flex justify-end text-sm text-gray-500 mt-1">
                                <span id="charCount">{{ old('description') ? strlen(old('description')) : 0 }}</span>/ 300 tekens
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <!-- Datum -->
                        <div>
                            <x-input-label for="date" :value="__('Datum')" />
                            <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" required :value="old('date')" />
                            <x-input-error class="mt-2" :messages="$errors->get('date')" />
                        </div>

                        <!-- Tijd -->
                        <div>
                            <x-input-label for="time" :value="__('Tijd')" />
                            <x-text-input id="time" name="time" type="time" class="mt-1 block w-full" required :value="old('time')" />
                            <x-input-error class="mt-2" :messages="$errors->get('time')" />
                        </div>

                        <!-- Locatie -->
                        <div>
                            <x-input-label for="location" :value="__('Locatie')" />
                            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" required :value="old('location')" />
                            <x-input-error class="mt-2" :messages="$errors->get('location')" />
                        </div>

                        <!-- Max deelnemers -->
                        <div>
                            <x-input-label for="max_participants" :value="__('Max deelnemers')" />
                            <x-text-input id="max_participants" name="max_participants" type="number" min="1" class="mt-1 block w-full" required :value="old('max_participants')" />
                            <x-input-error class="mt-2" :messages="$errors->get('max_participants')" />
                        </div>

                        <!-- Min deelnemers -->
                        <div>
                            <x-input-label for="min_participants" :value="__('Min deelnemers')" />
                            <x-text-input id="min_participants" name="min_participants" type="number" min="1" class="mt-1 block w-full" required :value="old('min_participants')" />
                            <x-input-error class="mt-2" :messages="$errors->get('min_participants')" />
                        </div>

                        <!-- Gasten toegestaan -->
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="gasten" name="gasten" value="1" class="rounded border-gray-300 text-covadisyellow focus:ring-covadisyellow" @if(old('gasten')) checked @endif>
                            <label for="gasten" class="text-sm text-gray-700">{{ __('Gasten toegestaan?') }}</label>
                        </div>

                        <!-- Foto's -->
                        <div>
                            <x-input-label for="activity_photos" :value="__('Foto\'s')" />
                            <input type="file" id="activity_photos" name="activity_photos[]" multiple accept="image/jpeg,image/png,image/webp" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow">
                            <p class="text-sm text-gray-500 mt-1">Je kunt meerdere afbeeldingen selecteren.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('activity_photos')" />
                        </div>

                        <!-- Opslaan knop -->
                        <x-primary-button>{{ __('Opslaan') }}</x-primary-button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Script voor live karaktertelling -->
    <script>
        function updateCharCount() {
            const textarea = document.getElementById('description');
            const count = textarea.value.length;
            document.getElementById('charCount').innerText = count;
        }
    </script>
</x-app-layout>
