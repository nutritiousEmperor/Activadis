<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        if($user->role == 'admin')
        {
            
            return view('admin.registerUser', compact('user'));
        } else {
            abort(403, 'Unauthorized.');
        }
    }

    public function storeUser(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $functie = $request->input('functie');
        $rol = $request->input('rol');

        // Maak migration aan met boolean met if password is changed.
        $wachtwoord = "CovadisLover123#";

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($wachtwoord),
        ]);

        return redirect()->route('admin.registerUser')->with('success', 'User created successfully!');
   
    }
}
