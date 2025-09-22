<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Account management') }}
        </h2>
    </x-slot>


    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="p-6 text-gray-900">
                    <div
                        class="flex flex-col sm:flex-row gap-4 items-start mb-6">
                        <a href="{{ route('admin.acounts') }}"
                            class="inline-flex items-center  bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            ↩ terug
                        </a>
                        <a href="{{ route('admin.medewerkers.functies.create') }}"
                            class="inline-flex items-center  bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            + Nieuwe functie
                        </a>
                    </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                <tr class="text-left text-gray-500">
                                    <th>Functie</th>
                                    <th>Aantal</th>
                                    <th>Acties</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($functions as $function)

                                    <tr class="border-t">
                                    <td class="font-medium">{{ $functions->naam }}</td>
                                    <td class="font-medium">0</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <a href="/admin/profile/{{ $user->id }}"class="text-blue-600 hover:underline">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4" data-lov-id="src/components/admin/ReservationManagement.tsx:234:24" data-lov-name="Eye" data-component-path="src/components/admin/ReservationManagement.tsx" data-component-line="234" data-component-file="ReservationManagement.tsx" data-component-name="Eye" data-component-content="%7B%22className%22%3A%22w-4%20h-4%22%7D"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </a>
                                        </div>
                                    </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                         </div>
                </div>
            </div>
        </div>
    </div>
            
</x-app-layout>
