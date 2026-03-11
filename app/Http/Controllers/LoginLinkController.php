<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;

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

        return back()->with('success', $loginLink);
    }

    public function login(Request $request, User $user)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        auth()->login($user);

        return redirect()->route('dashboard');
    }
}