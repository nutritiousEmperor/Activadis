<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Account beheer') }}
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-undo2-icon lucide-undo-2"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11"/></svg> Terug
                        </a>
                        <a href="{{ route('admin.medewerkers.functies.create') }}"
                            class="inline-flex items-center  bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            + Nieuwe Functie
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
                                    <td class="font-medium">{{ $function->naam }}</td>
                                    <td class="font-medium">{{ $function->users->count() }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.medewerkers.functies.show', $function->id) }}" class="text-blue-600 hover:underline">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4" data-lov-id="src/components/admin/ReservationManagement.tsx:234:24" data-lov-name="Eye" data-component-path="src/components/admin/ReservationManagement.tsx" data-component-line="234" data-component-file="ReservationManagement.tsx" data-component-name="Eye" data-component-content="%7B%22className%22%3A%22w-4%20h-4%22%7D"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </a>
                                            
                                            <a href="{{ route('admin.medewerkers.functies.edit', $function->id) }}" class="text-main-500 hover:underline">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="lucide lucide-pencil w-4 h-4"
                                                    fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.medewerkers.functies.destroy', $function->id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je dit wilt verwijderen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">
                                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                                        class="lucide lucide-trash w-4 h-4" 
                                                        fill="none" viewBox="0 0 24 24" 
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 6h18"></path>
                                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                        <path d="M10 11v6"></path>
                                                        <path d="M14 11v6"></path>
                                                    </svg>
                                                </button>
                                            </form>
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
