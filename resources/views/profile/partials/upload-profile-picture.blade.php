<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Upload foto') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Upload een nieuwe profiel foto. Aanbevolen formaat: 150x150px. Max 2MB.') }}
        </p>
    </header>

    <!-- Upload Form -->
    <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex items-center space-x-4">
            <!-- Current Profile Photo -->
            <img 
                src="{{ file_exists(public_path('profile_photos/pf-' . auth()->user()->id . '.jpg')) ? asset('profile_photos/pf-' . auth()->user()->id . '.jpg') : asset('profile_photos/default.jpg') }}" 
                alt="Current Profile Photo" 
                class="h-16 w-16 rounded-full object-cover border-2 border-white"
            >

            <!-- File Input -->
            <div>
                <x-input-label for="profile_photo" value="{{ __('Kies een profile foto') }}" class="block text-sm font-medium text-white"/>
                <input 
                    id="profile_photo" 
                    name="profile_photo" 
                    type="file" 
                    accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-900 file:border file:border-gray-900 file:rounded file:px-3 file:py-2 file:bg-white file:text-gray-700 hover:file:bg-gray-100"
                    required
                />
                <x-input-error :messages="$errors->get('profile_photo')" class="mt-2 text-white"/>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 ">
            <x-primary-button type="submit" class="">
                {{ __('Upload Photo') }}
            </x-primary-button>
        </div>
    </form>
</section>
