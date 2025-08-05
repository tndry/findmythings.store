<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SubmissionController;

// Mendaftarkan rute untuk otentikasi (login, register) & verifikasi email
Auth::routes(['verify' => true]);

// Grup rute untuk fitur 'Titip Jual' yang hanya bisa diakses
// oleh customer yang sudah login dan terverifikasi emailnya.
Route::middleware(['front', 'customer_auth:customer', 'verified'])
    ->controller(SubmissionController::class)
    ->group(function () {
        Route::get('/titip-jual', 'create')->name('submission.create');
        Route::post('/titip-jual', 'store')->name('submission.store');
    });