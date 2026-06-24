<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginAttempt;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class LoginLinkController extends Controller
{
    public function showForm()
    {
        return view('login-link.form');
    }

    public function sendLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => 'User',
                'password' => bcrypt('password'),
            ]
        );

        $loginLink = URL::temporarySignedRoute(
            'login-link.login',
            now()->addMinutes(10),
            ['user' => $user->id]
        );

        LoginAttempt::create([
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'status' => 'requested'
        ]);

        return back()->with('success', 'Magic link generated: ' . $loginLink);
    }

    public function login(Request $request, User $user)
    {
        if (!$request->hasValidSignature()) {
            LoginAttempt::create([
                'email' => $user->email,
                'ip_address' => $request->ip(),
                'status' => 'failed_invalid_link'
            ]);
            abort(401, 'Invalid or expired link');
        }

        Auth::login($user);

        LoginAttempt::create([
            'email' => $user->email,
            'ip_address' => $request->ip(),
            'status' => 'success'
        ]);

        return redirect()->route('login.success');
    }
}