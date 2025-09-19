<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Activiteit;

class ActiviteitenController extends Controller
{
    public function index()
    {
        // Haal activiteiten uit de database (tabel: activiteiten)
        $activiteiten = Activiteit::orderBy('datum')->orderBy('tijd')->get();

        $isLoggedIn = Auth::check();

        return view('activiteiten', [
            'isLoggedIn'   => $isLoggedIn,
            'activiteiten' => $activiteiten,
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
