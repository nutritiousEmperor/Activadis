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
        $validated = $request->validate([ 
            'title'            => 'required|string|max:255', 
            'description'      => 'nullable|string', 
            'date'             => 'required|date', 
            'time'             => 'required', 
            'location'         => 'required|string|max:255', 
            'max_participants' => 'nullable|integer|min:1', 
        ]); 

        Activity::create($validated); 

        return redirect()->route('admin.activiteiten.index') 
            ->with('success', 'Activiteit succesvol aangemaakt!'); 
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
} 
