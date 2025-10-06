@component('mail::message')

<p>
    Geachte heer/mevrouw {{ $data['name'] }},

    U bent ingeschreven voor de activiteit: {{ $data['activiteit']->title }}.
    Om u uit te schrijven voor {{ $data['activiteit']->title }}, kunt u op de onderstaande knop klikken.
</p>

@component('mail::button', ['url' => 'http://localhost:8000/activiteit/uitschrijven/' . $data['activiteit']->id])
    Uitschrijven
@endcomponent

@endcomponent
