<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return redirect('/');
    }
    
    public function sendResetLinkEmail()
    {
        return redirect('/');
    }
}
