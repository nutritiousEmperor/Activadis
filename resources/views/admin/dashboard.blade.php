<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Admin dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex flex-row items-center justify-between pb-2">
                        <h3 class="text-sm font-medium tracking-tight">Gebruikers</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="pt-0">
                        <div class="text-2xl font-bold">{{ $totalUsers }}</div>
                        <p class="text-xs text-gray-400">{{ $totalAdmins }} beheerders</p>
                    </div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex flex-row items-center justify-between pb-2">
                        <h3 class="text-sm font-medium tracking-tight">Activiteiten</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-activity-icon lucide-activity"><path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"/></svg>                    
                    </div>
                    <div class="pt-0">
                        <div class="text-2xl font-bold">4</div>
                    </div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex flex-row items-center justify-between pb-2">
                        <h3 class="text-sm font-medium tracking-tight">Inschrijvingen</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-pen-icon lucide-user-round-pen"><path d="M2 21a8 8 0 0 1 10.821-7.487"/><path d="M21.378 16.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z"/><circle cx="10" cy="8" r="5"/></svg>
                    </div>
                    <div class="pt-0">
                        <div class="text-2xl font-bold">4</div>
                    </div>
                </div>
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex flex-row items-center justify-between pb-2">
                        <h3 class="text-sm font-medium tracking-tight">Comming soon</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-code-xml-icon lucide-code-xml"><path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/></svg>                    
                    </div>
                    <div class="pt-0">
                        <div class="text-2xl font-bold"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
