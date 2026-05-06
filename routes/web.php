<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginLinkController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', function () {
    return view('welcome');
});

// Magic Login Form
Route::get('/login-link', [LoginLinkController::class, 'showForm'])
    ->name('login-link.form');

// Send Magic Link
Route::post('/login-link', [LoginLinkController::class, 'sendLink'])
    ->name('login-link.send');

// Magic Login (SIGNED URL)
Route::get('/login/{user}', [LoginLinkController::class, 'login'])
    ->middleware('signed')
    ->name('login-link.login');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::get('/login-success', function () {
        return view('login-success');
    })->name('login.success');

    // Logout (IMPORTANT for your project)
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login-link');
    })->name('logout');
});

require __DIR__ . '/auth.php';