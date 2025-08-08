<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\GoogleLoginController;

// Google OAuth routes mengikuti standar InnoShop
Route::get('/auth/google', [GoogleLoginController::class, 'redirect'])->name('google.redirect');
Route::get('/social/google/callback', [GoogleLoginController::class, 'callback'])->name('social.callback');

// Debug route untuk cek authentication status
Route::get('/debug-auth', function() {
    $data = [
        'web_auth' => auth()->check(),
        'customer_auth' => auth('customer')->check(),
        'web_user' => auth()->user(),
        'customer_user' => auth('customer')->user(),
    ];
    
    if (auth('customer')->check()) {
        $customer = auth('customer')->user();
        $data['customer_email_verified'] = $customer->hasVerifiedEmail();
        $data['customer_email_verified_at'] = $customer->email_verified_at;
    }
    
    return response()->json($data);
});

// Test route untuk cek akses submission tanpa middleware
Route::get('/test-submission', function() {
    return response()->json([
        'message' => 'Submission route accessible',
        'customer_auth' => auth('customer')->check(),
        'web_auth' => auth()->check(),
    ]);
});

// Mendaftarkan rute untuk otentikasi (login, register) & verifikasi email
Auth::routes(['verify' => true]);

// Grup rute untuk fitur 'Titip Jual' - TEMPORARY: Removed verified middleware for debugging
Route::middleware(['front', 'customer_auth:customer'])
    ->controller(SubmissionController::class)
    ->group(function () {
        Route::get('/titip-jual', 'create')->name('submission.create');
        Route::post('/titip-jual', 'store')->name('submission.store');
    });

// DEBUGGING: Temporary route without middleware
Route::get('/debug-titip-jual', [\App\Http\Controllers\SubmissionController::class, 'create'])->name('debug.submission.create');