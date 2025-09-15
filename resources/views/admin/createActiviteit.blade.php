<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nieuwe Activiteit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        covadisblue: '#0a1837',
                        covadisyellow: '#fbbf24',
                    }
                }
            }
        }
    </script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen flex flex-col bg-white"><!-- Achtergrond wit gemaakt -->

    <!-- Header -->
    <header class="bg-covadisyellow shadow-md"><!-- Header geel gemaakt -->
        <div class="max-w-7xl mx-auto px-9 py-7 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img src="/images/covadis-logo.png" alt="Covadis Logo" class="h-10">
                <span class="text-lg font-semibold text-covadisblue">Covadis</span>
            </div>
            <!-- Navigatie -->
            <nav class="hidden md:flex space-x-6 text-covadisblue font-medium">
                <a href="#" class="hover:text-white">Home</a>
                <a href="#" class="hover:text-white">Activiteiten</a>
                <a href="#" class="hover:text-white">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-grow flex items-center justify-center px-4">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md">
            <h3 class="text-2xl font-bold mb-6 text-center text-covadisblue">Nieuwe Activiteit</h3>

            <!-- Formulier -->
            <form method="POST" action="{{ route('admin.activities.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Titel</label>
                    <input type="text" name="title" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Omschrijving</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Datum</label>
                    <input type="date" name="date" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Tijd</label>
                    <input type="time" name="time" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Locatie</label>
                    <input type="text" name="location" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow" required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Max deelnemers</label>
                    <input type="number" name="max_participants" min="1" class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-covadisyellow focus:border-covadisyellow" required>
                </div>

                <button type="submit" class="w-full bg-covadisyellow hover:bg-yellow-500 text-covadisblue font-semibold py-2 px-4 rounded-lg shadow-md transition">
                    Opslaan
                </button>
            </form>
        </div>
    </main>

    <!-- SweetAlert2 Scripts -->
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Succes!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#fbbf24',
                background: '#fff'
            });
        });
    </script>
    @endif

    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Oeps!',
                html: `
                    <ul style="text-align: left;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#fbbf24',
            });
        });
    </script>
    @endif

</body>
</html>
