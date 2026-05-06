<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class LoginLinkController extends Controller
{
    // Show login form
    public function showForm()
    {
        return view('login-link.form');
    }

    // Generate magic login link
    public function sendLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Create or get user
        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => 'User',
                'password' => bcrypt('password'),
            ]
        );

        // Create signed login URL (valid for 10 minutes)
        $loginLink = URL::temporarySignedRoute(
            'login-link.login',
            now()->addMinutes(10),
            ['user' => $user->id]
        );

        return back()->with('success', $loginLink);
    }

    // Login using magic link
    public function login(Request $request, User $user)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired link');
        }

        auth()->login($user);

        return redirect()->route('login.success');
    }
}