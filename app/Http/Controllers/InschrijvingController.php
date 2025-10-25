<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;


class InschrijvingController extends Controller
{
    public function confirm($token)
{
    $inschrijving = DB::table('inschrijvingen')
        ->where('confirmationToken', $token)
        ->first();

    if (!$inschrijving) {
        return redirect()->route('home')->withErrors(['msg' => 'Ongeldige of verlopen bevestigingslink.']);
    }

    // Update to confirmed
    DB::table('inschrijvingen')
        ->where('id', $inschrijving->id)
        ->update([
            'confirmed' => true,
            'confirmationToken' => null, // remove token
            'updated_at' => now(),
        ]);

    return view('inschrijving.bevestigd', [
        'activiteit' => Activity::find($inschrijving->activity_id),
        'email' => $inschrijving->guest_email,
    ]);
}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
