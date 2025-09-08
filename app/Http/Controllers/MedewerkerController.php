<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedewerkerController extends Controller
{
    public function index()
    {
        // voorlopig wat dummy data
        $activiteiten = [
            ['title' => 'Workshop Laravel', 'description' => 'Leer de basis van Laravel.', 'date' => '2025-09-10'],
            ['title' => 'Teambuilding', 'description' => 'Leuke activiteiten met het team.', 'date' => '2025-09-15'],
        ];

        return view('medewerkers.index', compact('activiteiten'));
    }
}
