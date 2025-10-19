<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Functie Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                
                <a href="{{ route('admin.medewerkers.functies.index') }}"
                    class="inline-flex items-center  bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-undo2-icon lucide-undo-2"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11"/></svg> Terug
                </a>

                <div class="mt-6 space-y-6">

                    <div>
                        <x-input-label :value="__('Functie Naam')" />
                        <p class="mt-1 text-gray-900 text-lg">{{ $function->naam }}</p>
                    </div>

                    <div>
                        <x-input-label :value="__('Aantal medewerkers')" />
                        <p class="mt-1 text-gray-900">{{ $userFunctions->count() }}</p>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                <tr class="text-left text-gray-500">
                                    <th>Naam</th>
                                    <th>Email</th>
                                    <th>Functie</th>
                                    <th>Role</th>
                                    <th>Acties</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $user)
                                    @if ($user->functies->pluck('naam')->map(fn($n) => strtolower($n))->contains(strtolower($function->naam)))


                                        

                                        <tr class="border-t">
                                        <td class="font-medium">{{ $user->name }}</td>
                                        <td>
                                            <div class="text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td>
                                            <div class="text-gray-500">
                                                @if($user->functies->count() > 0)
                                                    {{ $user->functies->pluck('naam')->join(', ') }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td class="flex gap-2 ml-2">
                                                <a href="/admin/profile/{{ $user->id }}" class="text-main-500 hover:underline">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="lucide lucide-pencil w-4 h-4"
                                                        fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 20h9"></path>
                                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.medewerkers.functieuser.destroy', ['id' => $user->id, 'showid' => $function->id]) }}" method="POST" data-swal-confirm="Weet je zeker dat je deze gebruiker wilt verwijderen?">
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
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                         </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
