<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Dashboard voor admin.
     */

    

    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $totalUsers  = User::count();
        $totalAdmins = User::where('role', 'admin')->count();

        return view('admin.dashboard', [
            'totalUsers'  => $totalUsers,
            'totalAdmins' => $totalAdmins,
        ]);
    }
     public function createActiviteit()
    {
        
        return view('admin.createActiviteit');
    }

    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $totalUsers  = User::count();
        $totalAdmins = User::where('role', 'admin')->count();

        return view('admin.dashboard', [
            'totalUsers'  => $totalUsers,
            'totalAdmins' => $totalAdmins,
        ]);
    }

    /**
     * Overzichtspagina met alle activiteiten.
     */
    public function activiteiten()
    {
        $activities = Activity::latest()->get();

        return view('admin.overzicht', compact('activities'));
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
            'max_participants' => 'nullable|integer|min:1',
        ]);

        Activity::create($validated);

        return redirect()->route('admin.activities.create')
                         ->with('success', 'Activiteit succesvol aangemaakt!');
    }
}
