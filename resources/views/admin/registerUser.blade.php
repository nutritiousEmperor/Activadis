<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Account registratie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('admin.partials.create-account')
                </div>
            </div>

          
            
</x-admin-layout>
