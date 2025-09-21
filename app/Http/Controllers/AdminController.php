<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class AdminController extends Controller
{
    /**
     * Formulierpagina om een nieuwe activiteit aan te maken.
     */
    public function index()
    {
        return view('admin.createActiviteit');
    }

    /**
     * Activiteit opslaan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'date'             => 'required|date',
            'time'             => 'required',
            'location'         => 'required|string|max:255',
            'max_participants' => 'nullable|integer',
        ]);

        Activity::create($validated);

        return redirect()->route('admin.activiteiten')
                 ->with('success', 'Activiteit aangemaakt!');
    }

    /**
     * Overzichtspagina met alle activiteiten.
     */
    public function activiteiten()
    {
        $activities = Activity::latest()->get();

        return view('admin.overzicht', compact('activities'));
    }
}
