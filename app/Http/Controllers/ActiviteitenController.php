<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActiviteitenController extends Controller
{
    public function index()
    {
        // Dummy data (in de toekomst uit database)
        $activiteiten = [
            [
                'id' => 1,
                'activiteitnaam' => 'Workshop Laravel',
                'locatie' => 'Utrecht',
                'inclusief_eten' => true,
                'omschrijving' => 'Leer de basis van Laravel.',
                'begin_tijd' => '10:00',
                'eind_tijd' => '16:00',
                'kosten' => 50.00,
                'toegang' => 'iedereen',
            ],
            [
                'id' => 2,
                'activiteitnaam' => 'Teambuilding',
                'locatie' => 'Amsterdam',
                'inclusief_eten' => false,
                'omschrijving' => 'Samen plezier maken en sterker worden als team.',
                'begin_tijd' => '13:00',
                'eind_tijd' => '17:00',
                'kosten' => 0.00,
                'toegang' => 'covadis',
            ],
        ];

        $isLoggedIn = Auth::check();

        // Filter: gast ziet alleen "iedereen", ingelogd ziet alles
        $zichtbare = $isLoggedIn
            ? $activiteiten
            : array_values(array_filter($activiteiten, fn($a) => ($a['toegang'] ?? 'iedereen') === 'iedereen'));

        return view('activiteiten', [
            'isLoggedIn'   => $isLoggedIn,
            'activiteiten' => $zichtbare,
        ]);
    }

    public function guestSignup(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => ['required', 'integer'],
            'email'       => ['required', 'email'],
        ]);

        // normaal: opslaan in database of mailen
        Log::info('Gast ingeschreven', $validated);

        return back()->with('success', 'Bedankt! We hebben je inschrijving ontvangen.');
    }

    public function authSignup(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => ['required', 'integer'],
        ]);

        Log::info('Medewerker ingeschreven', [
            'user_id'     => Auth::id(),
            'activity_id' => $validated['activity_id'],
        ]);

        return back()->with('success', 'Je bent ingeschreven!');
    }

    
    
}
