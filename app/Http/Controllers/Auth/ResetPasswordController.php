<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    public function showResetForm()
    {
        return redirect('/');
    }
    
    public function reset()
    {
        return redirect('/');
    }
}
