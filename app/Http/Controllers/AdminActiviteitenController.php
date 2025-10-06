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
            $directory = public_path('activity_photos/' . $activity->id);
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
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'date'             => 'required|date',
            'time'             => 'required',
            'location'         => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'gasten'           => 'nullable|boolean',
        ]);

        $data['gasten'] = $request->boolean('gasten');

        $activity = Activity::findOrFail($id);
        $activity->update($data);

        return redirect()->route('admin.activiteiten.index')
            ->with(['success', 'Activiteit succesvol bijgewerkt!', 'go_index' => true]);
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

    public function photosUpload(Request $request, Activity $activity)
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $dir = public_path('activity_photos/' . $activity->id);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $count = 0;
        foreach ((array) $request->file('photos') as $file) {
            if (!$file || !$file->isValid()) continue;

            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) $ext = 'jpg';

            $name = uniqid('photo-') . '.' . $ext;
            $file->move($dir, $name);
            $count++;
        }

        return back()->with('success', $count.' foto'.($count===1?'':'’s').' toegevoegd.');
    }

    public function photosDelete(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'files'   => 'required|array',
            'files.*' => 'string',
        ]);

        $dir = public_path('activity_photos/' . $activity->id);
        $deleted = 0;

        foreach ($data['files'] as $f) {
            $file = basename($f); // geen pad-trucs
            if (!preg_match('/\.(jpe?g|png|webp)$/i', $file)) continue;

            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_file($path)) {
                @unlink($path);
                $deleted++;
            }
        }

        return back()->with('success', $deleted . ' foto' . ($deleted === 1 ? '' : '’s') . ' verwijderd.');
    }
}
