@component('mail::message')

<p>
    Geachte heer/mevrouw {{ $data['name'] }},

    U bent bijna ingeschreven voor de activiteit: {{ $data['activiteit']->title }}.
    Om uw inschrijving te voltooien zult u op de onderstaande knop moeten klikken.
</p>

@component('mail::button', ['url' => URL::signedRoute('inschrijving.confirm', [
    'token' => $data['token']
])])
    Bevestig Inschrijving
@endcomponent


@endcomponent
