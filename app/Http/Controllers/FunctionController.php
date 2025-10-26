<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Functie;
use App\Models\UserFunction;
use App\Models\User;

class FunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $functions = Functie::all();
        $userFunction = UserFunction::all();
        return view('admin.medewerkers.functions.index', compact('functions', 'userFunction'));
    }
    
    public function show(string $id)
    {
        $users = User::all();
        // Zoek de functie op basis van id of geef een 404 als deze niet bestaat
        $function = Functie::findOrFail($id);
        $userFunctions = UserFunction::where('functie_id', $id)->get();

        // Geef de show-view terug met het gevonden function object
        return view('admin.medewerkers.functions.show', compact('function', 'userFunctions', 'users'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.medewerkers.functions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'naam' => 'required|string|max:255',
        ]);

        Functie::create([
            'naam' => $request->naam,
        ]);

        return redirect()->route('admin.medewerkers.functies.index')
                         ->with('success', 'Functie succesvol aangemaakt!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $function = Functie::findOrFail($id);
        return view('admin.medewerkers.functions.edit', compact('function'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'naam' => 'required|string|max:255',
        ]);

        $function = Functie::findOrFail($id);
        $function->update([
            'naam' => $request->naam,
        ]);

        return redirect()->route('admin.medewerkers.functies.index')
                         ->with('success', 'Functie succesvol bijgewerkt!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $exists = UserFunction::where('functie_id', $id)->exists();
        if ($exists == true) {
            return redirect()
                ->back()
                ->with('error', 'Er is nog een gebruiker gekoppelt aan deze functie!');
        }


        $function = Functie::findOrFail($id);
        $function->delete();

        return redirect()->route('admin.medewerkers.functies.index')
                         ->with('success', 'Functie succesvol verwijderd!');
    }

    
    public function destroyUser(string $id, string $showid)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()
            ->route('admin.medewerkers.functies.show', $showid)
            ->with('success', 'Gebuiker succesvol verwijderd!');

    }
}
