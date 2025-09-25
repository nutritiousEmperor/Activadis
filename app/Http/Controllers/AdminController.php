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
}
