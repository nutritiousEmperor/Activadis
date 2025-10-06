<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\createPassword;

class MailController extends Controller
{
    public function createPassword()
    {
        $password1 = 'test123';
        $name = "Natalie";
        Mail::to('activadis@outlook.com')->send(new createPassword($password1, $name));
        return view('welcome');
    }
}
