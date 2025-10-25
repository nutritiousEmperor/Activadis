<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class AdminActiviteitenController extends Controller
{
    /**
     * ========= Foto helper-functies (geen DB, alleen files) =========
     */
    private function photoDir(Activity $activity): string
    {
        return public_path('activity_photos/' . $activity->id . '/');
    }

    private function orderPath(Activity $activity): string
    {
        return $this->photoDir($activity) . '.order.json';
    }

    private function allowedExt(): array
    {
        return ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    }

    /** Bestandsnamen (alleen images) ophalen als array van strings */
    private function listPhotoNames(Activity $activity): array
    {
        $dir = $this->photoDir($activity);
        if (!is_dir($dir)) return [];

        $files = array_values(array_filter(scandir($dir) ?: [], function ($f) use ($dir) {
            if ($f === '.' || $f === '..' || $f === '.order.json') return false;
            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            return is_file($dir . $f) && in_array($ext, $this->allowedExt());
        }));

        return $files;
    }

    /** .order.json lezen (array van bestandsnamen) */
    private function readOrder(Activity $activity): array
    {
        $path = $this->orderPath($activity);
        if (is_file($path)) {
            $json = json_decode(@file_get_contents($path), true);
            if (is_array($json)) return $json;
        }
        return [];
    }

    /** .order.json wegschrijven */
    private function writeOrder(Activity $activity, array $names): void
    {
        $dir = $this->photoDir($activity);
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        @file_put_contents($this->orderPath($activity), json_encode(array_values($names), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /** Namen sorteren o.b.v. .order.json; onbekenden alfabetisch erachter */
    private function ordered(Activity $activity): array
    {
        $all   = collect($this->listPhotoNames($activity));
        $order = collect($this->readOrder($activity));

        $inOrder   = $order->filter(fn($n) => $all->contains($n))->values();
        $remaining = $all->diff($inOrder)->sort()->values();

        return $inOrder->concat($remaining)->all();
    }

    /**
     * =================== Standaard resource actions ===================
     */

    /** Display a listing of the resource. */
    public function index()
    {
        $activities = Activity::latest()->get();
        return view('admin.activiteiten.overzicht', compact('activities'));
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        return view('admin.activiteiten.createActiviteit');
    }

    /** Store a newly created resource in storage. */
    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'date'              => 'required|date',
            'time'              => 'required',
            'location'          => 'required|string|max:255',
            'max_participants'  => 'required|integer|min:1',
             'min_participants'  => ['required','integer','min:0'],
            'gasten'            => 'nullable|boolean',
            'activity_photos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // Activiteit eerst opslaan
        $activity = Activity::create([
            'title'            => $request->title,
            'description'      => $request->description,
            'date'             => $request->date,
            'time'             => $request->time,
            'location'         => $request->location,
            'max_participants' => $request->max_participants,
            'min_participants' => $request->min_participants,
            'gasten'           => $request->has('gasten'),
        ]);

        // Foto’s wegschrijven
        if ($request->hasFile('activity_photos')) {
            $directory = $this->photoDir($activity);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            foreach ($request->file('activity_photos') as $file) {
                if (!$file || !$file->isValid()) continue;
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, $this->allowedExt())) $ext = 'jpg';
                $filename = uniqid('photo-') . '.' . $ext;
                $file->move($directory, $filename);
            }
        }

        return redirect()->route('admin.activiteiten.index')->with('success', 'Activiteit succesvol aangemaakt!');
    }

    /** Show the form for editing the specified resource. */
    public function edit(string $id)
    {
        $activity = Activity::findOrFail($id);
        return view('admin.activiteiten.editActiviteit', compact('activity'));
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'date'             => 'required|date',
            'time'             => 'required',
            'location'         => 'required|string|max:255',
            'max_participants' => 'nullable|integer|min:1',
            'min_participants' => 'nullable|integer|min:1|lte:max_participants',
            'gasten'           => 'nullable|boolean',
        ]);

        $data['gasten'] = $request->boolean('gasten');

        $activity = Activity::findOrFail($id);
        $activity->update($data);

        // let op: with() verwacht key => value, niet losse array
        return redirect()->route('admin.activiteiten.index')
            ->with('success', 'Activiteit succesvol bijgewerkt!');
    }

    /** Remove the specified resource from storage. */
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

        $directory = $this->photoDir($activity);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $ext = strtolower($request->file('activity_photo')->getClientOriginalExtension());
        if (!in_array($ext, $this->allowedExt())) $ext = 'jpg';

        $filename = uniqid('photo-') . '.' . $ext;
        $request->file('activity_photo')->move($directory, $filename);

        return back()->with('status', 'Foto toegevoegd.');
    }

    public function photosUpload(Request $request, Activity $activity)
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $dir = $this->photoDir($activity);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $count = 0;
        foreach ((array) $request->file('photos') as $file) {
            if (!$file || !$file->isValid()) continue;

            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, $this->allowedExt())) $ext = 'jpg';

            $name = uniqid('photo-') . '.' . $ext;
            $file->move($dir, $name);
            $count++;
        }

        return back()->with('success', $count . ' foto' . ($count === 1 ? '' : '’s') . ' toegevoegd.');
    }

    public function photosDelete(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'files'   => 'required|array',
            'files.*' => 'string',
        ]);

        $dir = $this->photoDir($activity);
        $toDelete = collect($data['files'])->map(fn($f) => basename($f));
        $deleted = 0;

        foreach ($toDelete as $file) {
            if (!preg_match('/\.(jpe?g|png|webp|gif)$/i', $file)) continue;

            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_file($path)) {
                @unlink($path);
                $deleted++;
            }
        }

        // .order.json opschonen zodat er geen spooknamen blijven staan
        $order = collect($this->readOrder($activity))
            ->reject(fn($n) => $toDelete->contains($n))
            ->values()
            ->all();
        $this->writeOrder($activity, $order);

        return back()->with('success', $deleted . ' foto' . ($deleted === 1 ? '' : '’s') . ' verwijderd.');
    }

    public function show($id)
    {
        $activity = Activity::findOrFail($id);
        return view('admin.activiteiten.detailActiviteit', compact('activity'));
    }

    /**
     * =================== Drag & Drop sorteren (zelfde controller) ===================
     */

    /** GET: sorteerpagina met drag & drop */
    public function photosSort(Activity $activity)
    {
        $names = $this->ordered($activity);
        $items = array_map(function ($name) use ($activity) {
            return [
                'name' => $name,
                'url'  => url('activity_photos/' . $activity->id . '/' . $name),
            ];
        }, $names);

        return view('admin.activiteiten.photos-sort', [
            'activity' => $activity,
            'photos'   => $items,
        ]);
    }

    /** POST: volgorde opslaan vanuit de front-end */
    public function photosOrder(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'order'   => ['required', 'array', 'min:1'],
            'order.*' => ['string'],
        ]);

        $existing = collect($this->listPhotoNames($activity));
        $clean = collect($data['order'])
            ->map(fn($n) => basename($n))
            ->filter(fn($n) => $existing->contains($n))
            ->unique()
            ->values()
            ->all();

        if (empty($clean)) {
            return response()->json(['message' => 'Lege of ongeldige volgorde'], 422);
        }

        $dir = $this->photoDir($activity);
        if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
            return response()->json(['message' => 'Map aanmaken mislukt'], 500);
        }

        $ok = @file_put_contents($this->orderPath($activity), json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        if ($ok === false) {
            return response()->json(['message' => 'Opslaan mislukt (rechten?)'], 500);
        }

        return response()->json(['message' => 'Volgorde opgeslagen', 'order' => $clean], 201);
    }
}
