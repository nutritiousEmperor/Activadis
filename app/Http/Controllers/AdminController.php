<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class AdminController extends Controller
{
    // Admin dashboard pagina
    public function index()
    {
        $activities = Activity::latest()->get();

        return view('admin.index', compact('activities'));
    }

    // Activiteit opslaan
   public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'time' => 'required',
        'location' => 'required|string|max:255',
        'max_participants' => 'required|integer|min:1',
    ]);

    Activity::create([
        'title' => $request->title,
        'description' => $request->description,
        'date' => $request->date,
        'time' => $request->time,
        'location' => $request->location,
        'max_participants' => $request->max_participants,
    ]);

    return redirect()->route('admin.index')->with('success', 'Activiteit succesvol aangemaakt!');
}

}
