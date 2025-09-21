<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">

                <div class="flex justify-center btn1">
                    <a href="{{ route('admin.registerUser') }}">
                        <x-primary-button>{{ __('Registreer account') }}</x-primary-button>
                    </a>
                </div>

                    <table>
                    <thead>
                        <tr>
                            <th>Naam</th>
                            <th>Email</th>
                            <th>Functie</th>
                            <th>Rol</th>
                            <th>Profiel</th>
                        </tr>
                    </thead>
                        @foreach ($users as $user)
                            <tr>
                                <td class="border px-4 py-2">{{ $user->name }}</td>
                                <td class="border px-4 py-2">{{ $user->email }}</td>
                                <td class="border px-4 py-2">{{ $user->functie ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ ucfirst($user->role) }}</td>
                                <td class="border px-4 py-2"><a href="/admin/profile/{{ $user->id }}" class="text-indigo-600 hover:text-indigo-900">Profiel</a></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>     
            
</x-app-layout>

<style>
    table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #7a3f3f;
            padding: 10px;
            text-align: center;
        }

        .btn1 {
            padding-bottom: 20px;
        }
</style>