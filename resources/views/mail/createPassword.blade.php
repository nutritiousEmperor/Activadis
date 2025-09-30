@component('mail::message')

<p>
    Geachte heer/mevrouw {{ $name }},

    Om uw account aan te maken zult u op de onderstaande knop moeten klikken. 
    Vervolgens krijgt u de mogelijkheid om een wachtwoord in te stellen.
</p>

@component('mail::button', ['url' => 'https://google.com'])
    Wachtwoord aanpassen
@endcomponent

@endcomponent