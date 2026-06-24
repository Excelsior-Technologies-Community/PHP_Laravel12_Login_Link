<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginLinkController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login-link', [LoginLinkController::class, 'showForm'])
    ->name('login-link.form');

Route::post('/login-link', [LoginLinkController::class, 'sendLink'])
    ->name('login-link.send');

Route::get('/login/{user}', [LoginLinkController::class, 'login'])
    ->middleware('signed')
    ->name('login-link.login');

// Authenticated Routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::get('/login-success', function () {
        return view('login-success');
    })->name('login.success');

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login-link')->with('success', 'You have been logged out.');
    })->name('logout');
});

require __DIR__ . '/auth.php';