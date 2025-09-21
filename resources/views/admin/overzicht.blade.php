<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overzicht Activiteiten</title>
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
</head>
<body class="min-h-screen flex flex-col bg-white">

    <!-- Header -->
    <header class="bg-covadisyellow shadow-md">
        <div class="max-w-7xl mx-auto px-9 py-7 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img src="/images/covadis-logo.png" alt="Covadis Logo" class="h-10">
                <span class="text-lg font-semibold text-covadisblue">Covadis</span>
            </div>
            <!-- Navigatie -->
            <nav class="hidden md:flex space-x-6 text-covadisblue font-medium">
                <a href="#" class="hover:text-white">Home</a>
                <a href="{{ route('admin.activiteiten') }}" class="hover:text-white">Activiteiten</a>
                <a href="#" class="hover:text-white">Contact</a>
            </nav>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-grow px-4 py-10 max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-covadisblue mb-8 text-center">Overzicht van Activiteiten</h1>

        @if($activities->count() > 0)
            <div class="space-y-6">
                @foreach($activities as $activity)
                    <div class="bg-white rounded-2xl shadow-2xl p-6">
                        <h2 class="text-2xl font-semibold text-covadisblue mb-3">{{ $activity->title }}</h2>
                        <div class="text-gray-700 space-y-1">
                            <p><strong>Omschrijving:</strong> {{ $activity->description ?? 'Geen omschrijving' }}</p>
                            <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($activity->date)->format('d-m-Y') }}</p>
                            <p><strong>Tijd:</strong> {{ \Carbon\Carbon::parse($activity->time)->format('H:i') }}</p>
                            <p><strong>Locatie:</strong> {{ $activity->location }}</p>
                            <p><strong>Max deelnemers:</strong> {{ $activity->max_participants ?? 'Onbeperkt' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500">Er zijn nog geen activiteiten toegevoegd.</p>
        @endif
    </main>

</body>
</html>
