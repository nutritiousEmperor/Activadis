<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl leading-tight">
      {{ __('Inschrijving Bevestigd') }}
    </h2>
  </x-slot>
  <div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 text-center">
          <!-- Success Icon -->
          <svg class="mx-auto mb-4 w-16 h-16 text-green-500" fill="none" stroke="currentColor"
          viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M5 13l4 4L19 7">
            </path>
          </svg>
          <h3 class="text-2xl font-bold mb-2">
            Inschrijving Bevestigd!
          </h3>
          <p class="mb-4 text-gray-700">
            Bedankt
            <span class="font-medium">
              {{ $email }}
            </span>
            , je inschrijving voor
            <strong>
              {{ $activiteit->title }}
            </strong>
            is succesvol bevestigd!
          </p>
          <a href="{{ route('activiteiten.index') }}" class="inline-block mt-4 px-6 py-2 bg-covadisyellow text-covadisblue font-semibold rounded-lg shadow-md hover:bg-yellow-400 transition">
            Terug naar activiteiten
          </a>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>