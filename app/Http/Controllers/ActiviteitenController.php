<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Activity;

class ActiviteitenController extends Controller
{
    /**
     * Toon alle activiteiten (gesorteerd op datum/tijd)
     */
    public function index()
    {
        $activities = Activity::orderBy('date')->orderBy('time')->get();

        return view('activities.index', [
            'isLoggedIn' => Auth::check(),
            'activities' => $activities,
        ]);
    }

    /**
     * Form om een activity aan te maken
     */
    public function create()
    {
        return view('activities.create');
    }

    /**
     * Sla een nieuwe activity op
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => ['required','string','max:255'],
            'description'      => ['nullable','string'],
            'date'             => ['nullable','date'],
            'time'             => ['nullable','date_format:H:i'],
            'location'         => ['nullable','string','max:255'],
            'max_participants' => ['nullable','integer','min:1'],
        ]);

        Activity::create($data);

        return redirect()->route('activities.index')->with('success', 'Activity aangemaakt.');
    }

    /**
     * Toon één activity
     */
    public function show(Activity $activity)
    {
        return view('activities.show', compact('activity'));
    }

    /**
     * Form om een activity te bewerken
     */
    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    /**
     * Update een activity
     */
    public function update(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'title'            => ['required','string','max:255'],
            'description'      => ['nullable','string'],
            'date'             => ['nullable','date'],
            'time'             => ['nullable','date_format:H:i'],
            'location'         => ['nullable','string','max:255'],
            'max_participants' => ['nullable','integer','min:1'],
        ]);

        $activity->update($data);

        return redirect()->route('activities.index')->with('success', 'Activity bijgewerkt.');
    }

    /**
     * Verwijder een activity
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Activity verwijderd.');
    }

    /**
     * Gast-inschrijving (blijft gelijk, gebruikt activity_id)
     */
    public function guestSignup(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => ['required','integer','exists:activities,id'],
            'email'       => ['required','email'],
        ]);

        Log::info('Gast ingeschreven', $validated);

        return back()->with('success', 'Bedankt! We hebben je inschrijving ontvangen.');
    }

    /**
     * Inschrijving ingelogde gebruiker
     */
    public function authSignup(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => ['required','integer','exists:activities,id'],
        ]);

        Log::info('Medewerker ingeschreven', [
            'user_id'     => Auth::id(),
            'activity_id' => $validated['activity_id'],
        ]);

        return back()->with('success', 'Je bent ingeschreven!');
    }
}
