<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Functie;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if($user->role == 'admin')
        {
            $functions = Functie::all();
            $users = User::all();
            return view('admin.medewerkers.accounts', compact('users', 'functions'));
        } else {
            abort(403, 'Unauthorized.');
        }
    }

    public function show()
    {
        $user = Auth::user();
        if($user->role == 'admin')
        {
            $functions = Functie::all();
            
            return view('admin.medewerkers.registerUser', compact('user', 'functions'));
        } else {
            abort(403, 'Unauthorized.');
        }
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'functions' => 'array',            // functies is een array
            'functions.*' => 'exists:functies,id',
        ]);

        $password = "Covadis123#"; // standaard wachtwoord

        // User aanmaken en opslaan in variabele
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role,
        ]);

        // Functies synchroniseren via pivot table
        $user->functies()->sync($request->functions ?? []);

        return redirect()->route('admin.registerUser')
                        ->with('success', 'User created successfully!');
    }


    public function profile($id)
    {
        $user = User::findOrFail($id);
        $functions = Functie::all();
        $userFunctions = $user->functies->pluck('id')->toArray();

        return view('admin.medewerkers.profile', compact('user', 'functions', 'userFunctions'));
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
    // Find the user
    $user = User::findOrFail($id);



    // Validate the input
    $request->validate([
        'functions' => 'array', // functions is een array
        'functions.*' => 'exists:functies,id', // elk ID moet bestaan
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id, // ignore current user's email
        'role' => 'required|in:user,admin',
        // optionally, if you allow password changes:
        // 'password' => 'nullable|string|min:8|confirmed',
    ]);

    
    // functies synchroniseren
     $user->functies()->sync($request->functions ?? []);

    // Update user
    $user->update([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'role' => $request->input('role'),
        // optionally:
        // 'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
    ]);

    return redirect()->route('admin.profile', $user->id)
                     ->with('status', 'profile-updated');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
