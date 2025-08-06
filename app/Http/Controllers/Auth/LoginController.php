<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return redirect('/');
    }
    
    public function login()
    {
        return redirect('/');
    }
    
    public function logout()
    {
        return redirect('/');
    }
}
