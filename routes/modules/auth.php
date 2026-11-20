<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Autentikasi & Hak Akses (Modul Fadilah)
|--------------------------------------------------------------------------
*/

// Rute untuk pengguna yang belum login (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

    Route::get('/lupa-sandi', [ForgotPasswordController::class, 'showRequestForm'])->name('password.forgot');
    Route::post('/lupa-sandi', [ForgotPasswordController::class, 'sendOtp'])->name('password.otp.send')->middleware('throttle:3,1');
    Route::get('/lupa-sandi/reset', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/lupa-sandi/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset.update')->middleware('throttle:10,1');
});

// Rute untuk pengguna yang sudah terotentikasi (Auth)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
