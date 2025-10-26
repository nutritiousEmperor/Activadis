<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="font-semibold text-xl leading-tight"> 
            {{ __('Activiteiten beheer') }} 
        </h2> 
    </x-slot> 

    <div class="py-12"> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> 
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"> 
                <div class="p-6 text-gray-900"> 

                    <!-- Knop nieuwe activiteit -->
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between mb-6"> 
                        <a href="{{ route('admin.activiteiten.create') }}" 
                           class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"> 
                            + Nieuwe Activiteit 
                        </a> 
                    </div> 

                    <div class="overflow-x-auto"> 
                        @if($activities->count() > 0) 
                            <table class="min-w-full text-sm"> 
                                <thead> 
                                    <tr class="text-left text-gray-500"> 
                                        <th>Titel</th> 
                                        <th>Datum</th> 
                                        <th>Locatie</th> 
                                        <th>Acties</th> 
                                    </tr> 
                                </thead> 
                                <tbody> 
                                    @foreach($activities as $activity) 
                                        <tr class="border-t"> 
                                            <td class="font-medium">{{ $activity->title }}</td> 
                                            <td>{{ \Carbon\Carbon::parse($activity->date)->format('d-m-Y') }}</td> 
                                            <td>{{ $activity->location }}</td> 
                                            <td> 
                                                <div class="flex items-center gap-2">
                                                    
                                                    <!-- Details knop -->
                                                    <a href="{{ route('admin.activiteiten.show', $activity->id) }}" 
                                                       class="text-blue-600 hover:underline" 
                                                       title="Details">                                                 
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4" data-lov-id="src/components/admin/ReservationManagement.tsx:234:24" data-lov-name="Eye" data-component-path="src/components/admin/ReservationManagement.tsx" data-component-line="234" data-component-file="ReservationManagement.tsx" data-component-name="Eye" data-component-content="%7B%22className%22%3A%22w-4%20h-4%22%7D"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle></svg>

                                                    </a> 

                                                    <!-- Bewerken knop --> 
                                                    <a href="{{ route('admin.activiteiten.edit', $activity->id) }}"  
                                                       class="text-main-500 hover:underline" 
                                                       title="Bewerken"> 
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="lucide lucide-pencil w-4 h-4"
                                                            fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M12 20h9"></path>
                                                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                                                        </svg>
                                                    </a> 

                                                    <!-- Verwijderen knop --> 
                                                    <form action="{{ route('admin.activiteiten.destroy', $activity->id) }}" 
                                                          method="POST" 
                                                          class="delete-form inline"> 
                                                        @csrf 
                                                        @method('DELETE') 
                                                        <button type="button" class="delete-button text-red-600 " title="Verwijderen"> 
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
                        @else 
                            <p class="text-center text-gray-500">Er zijn nog geen activiteiten toegevoegd.</p> 
                        @endif 
                    </div> 

                </div> 
            </div> 
        </div> 
    </div> 

    <!-- SweetAlert2 script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const deleteButtons = document.querySelectorAll(".delete-button");

            deleteButtons.forEach(button => {
                button.addEventListener("click", function () {
                    let form = this.closest("form");

                    Swal.fire({
                        title: "Weet je het zeker?",
                        text: "Je kunt dit niet ongedaan maken!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ja, verwijderen!",
                        cancelButtonText: "Annuleren"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
