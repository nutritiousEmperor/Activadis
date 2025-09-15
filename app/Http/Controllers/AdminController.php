<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
    }
}
