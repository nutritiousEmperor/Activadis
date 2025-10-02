<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;


class AdminActiviteitenController extends Controller
{
    /** 
     * Display a listing of the resource. 
     */
    public function index()
    {
        $activities = Activity::latest()->get();
        return view('admin.activiteiten.overzicht', compact('activities'));
    }

    /** 
     * Show the form for creating a new resource. 
     */
    public function create()
    {
        return view('admin.activiteiten.createActiviteit');
    }

    /** 
     * Store a newly created resource in storage. 
     */
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'time' => 'required',
        'location' => 'required|string|max:255',
        'max_participants' => 'required|integer|min:1',
        'gasten' => 'nullable|boolean',
       'activity_photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

    ]);

    // Activiteit eerst opslaan
    $activity = Activity::create([
        'title' => $request->title,
        'description' => $request->description,
        'date' => $request->date,
        'time' => $request->time,
        'location' => $request->location,
        'max_participants' => $request->max_participants,
        'gasten' => $request->has('gasten'),
    ]);

    // Foto’s wegschrijven
    if ($request->hasFile('activity_photos')) {
        $directory = public_path('activity_photos/'.$activity->id);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        foreach ($request->file('activity_photos') as $file) {
            $filename = uniqid('photo-') . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
        }
    }

    return redirect()->route('admin.activiteiten.index')->with('success', 'Activiteit succesvol aangemaakt!');
}

    /** 
     * Show the form for editing the specified resource. 
     */
    public function edit(string $id)
    {
        $activity = Activity::findOrFail($id);
        return view('admin.activiteiten.editActiviteit', compact('activity'));
    }

    /** 
     * Update the specified resource in storage. 
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'date'             => 'required|date',
            'time'             => 'required',
            'location'         => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
        ]);

        $activity = Activity::findOrFail($id);
        $activity->update($validated);

        return redirect()->route('admin.activiteiten.index')
            ->with('success', 'Activiteit succesvol bijgewerkt!');
    }

    /** 
     * Remove the specified resource from storage. 
     */
    public function destroy(string $id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return redirect()->route('admin.activiteiten.index')
            ->with('success', 'Activiteit succesvol verwijderd!');
    }


    public function uploadPhoto(Request $request, Activity $activity)
    {
        $request->validate([
            'activity_photo' => 'required|image|max:4096',
        ]);

        $directory = public_path('activity_photos/' . $activity->id);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = uniqid('photo-') . '.' . $request->file('activity_photo')->getClientOriginalExtension();
        $request->file('activity_photo')->move($directory, $filename);

        return back()->with('status', 'Foto toegevoegd.');
    }
}
