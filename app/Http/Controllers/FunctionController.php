<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Functie;


class FunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $functions = Functie::all(); // let op: gebruik Functie i.p.v. Functions
        return view('admin.medewerkers.functions.index', compact('functions'));
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

        Functions::create([
            'naam' => $request->naam,
        ]);

        return redirect()->route('admin.medewerkers.functies.create')
                        ->with('status', 'functie-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
