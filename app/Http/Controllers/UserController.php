<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            $users = User::all();
            return view('admin.medewerkers.accounts', compact('users'));
        } else {
            abort(403, 'Unauthorized.');
        }
    }

    public function show()
    {
        $user = Auth::user();
        if($user->role == 'admin')
        {
            
            return view('admin.medewerkers.registerUser', compact('user'));
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

        $name = $request->input('name');
        $email = $request->input('email');
        $functie = $request->input('functie');
        $role = $request->input('role');

        // Maak migration aan met boolean met if password is changed.
        $wachtwoord = "Covadis123#";

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($wachtwoord),
            'functie' => $functie,
            'role' => $role
        ]);

        return redirect()->route('admin.registerUser')->with('success', 'User created successfully!');
    }

    public function profile($id)
    {
        $user = User::findOrFail($id);
        return view('admin.medewerkers.profile', compact('user'));
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
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id, // ignore current user's email
        'functie' => 'nullable|string|max:255',
        'role' => 'required|in:user,admin',
        // optionally, if you allow password changes:
        // 'password' => 'nullable|string|min:8|confirmed',
    ]);

    // Update user
    $user->update([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'functie' => $request->input('functie'),
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
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.acounts')->with('success', 'User deleted successfully!');
    }
}
