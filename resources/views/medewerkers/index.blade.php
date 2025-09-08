@extends('layouts.app') {{-- of een andere layout als je die hebt --}}

@section('content')
<div class="container">
    <h1>Medewerker activiteiten</h1>

    @foreach($activiteiten as $activiteit)
        <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
            <h3>{{ $activiteit['title'] }}</h3>
            <p>{{ $activiteit['description'] }}</p>
            <p><strong>Datum:</strong> {{ $activiteit['date'] }}</p>

            <button>Inschrijven</button>
        </div>
    @endforeach
</div>
@endsection
