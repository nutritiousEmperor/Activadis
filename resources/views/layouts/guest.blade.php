<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="icon" href="{{ asset('img/logo_fav.svg') }}" type="image/svg+xml">


    </head>
    <body class="font-sans text-gray-900 antialiased">    
        @if ($errors->any())
            <script>
                @foreach ($errors->all() as $error)
                    Swal.fire({
                        position: "top",
                        icon: "error",
                        title: "{{ $error }}",
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                @endforeach
            </script>
        @endif
        @if (session('error'))
            <script>
                Swal.fire({
                    position: "top",
                    icon: "error",
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 4000,
                    toast: true
                });
            </script>
        @endif
        @if (session('success'))
            <script>
                Swal.fire({
                    position: "top",
                    icon: "success",
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true
                });
            </script>
        @endif
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-secondary">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 shadow-md overflow-hidden sm:rounded-lg bg-gray-100">
                {{ $slot }}
            </div>
        </div>
    </body>
    <script>
            
        document.querySelectorAll('form[data-swal-confirm]').forEach(form => {
            form.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = form.dataset.swalConfirm || 'Weet je het zeker?';

            Swal.fire({
                title: 'Weet je het zeker?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ja, verwijderen',
                cancelButtonText: 'Nee, annuleren',
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                });
            });
        });
    </script>
</html>
