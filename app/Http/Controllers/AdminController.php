<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use App\Models\Activity;

class AdminController extends Controller
{
  
     public function dashboard()
    {
        $user = Auth::user();
        if($user->role == 'admin')
        {       
            $totalUsers = User::count();
            $totalAdmins = User::where('role', 'admin')->count();

            return view('admin.dashboard', ['totalUsers'   => $totalUsers, 'totalAdmins'   => $totalAdmins]);
        } else {
            abort(403, 'Unauthorized.');
        }
        
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


