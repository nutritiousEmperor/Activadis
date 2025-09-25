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
                        class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between mb-6">
                        <a href="{{ route('admin.registerUser') }}"
                            class="inline-flex items-center  bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            + Nieuwe Medewerker
                        </a>
                    </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                <tr class="text-left text-gray-500">
                                    <th>Naam</th>
                                    <th>Email</th>
                                    <th>functie</th>
                                    <th>Role</th>
                                    <th>Acties</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $user)

                                    <tr class="border-t">
                                    <td class="font-medium">{{ $user->name }}</td>
                                    <td>
                                        <div class="text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    <td>
                                        <div class="text-gray-500">{{ $user->functie ?? '-' }}</div>
                                    </td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td class="flex gap-2 ml-2">
                                        <div class="flex items-center gap-2">
                                            <a href="/admin/profile/{{ $user->id }}"class="text-blue-600 hover:underline">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                                            </a>
                                        </div>


                                        <div class="flex items-center gap-2">
                                        <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
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
