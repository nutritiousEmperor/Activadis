<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if($user->role == 'admin')
        {
            return view('admin.dashboard');
        } else {
            abort(403, 'Unauthorized.');
        }
        
    }
}
