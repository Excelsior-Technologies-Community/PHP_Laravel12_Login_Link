# PHP_Laravel12_Login_Link

## Introduction

PHP_Laravel12_Login_Link is a Laravel 12 project demonstrating **passwordless authentication** using magic login links.

Users can log in securely **without a password**. Instead, a **temporary signed URL** is generated that authenticates the user automatically.

The project is designed for **local development** and includes:

- Custom login link controller
- Temporary signed URLs with automatic expiry
- Tailwind CSS styled login page
- Dashboard access after login
- Optional extension for sending login links via email
- Optional: integration with Spatie Laravel Login Link package

> Note: In this project, the login link functionality is implemented using Laravel’s built-in URL::temporarySignedRoute() method. Installing the Spatie package is optional and not required for this project to work.

---

## Project Overview

PHP_Laravel12_Login_Link demonstrates:

#### 1) Passwordless login workflow
- User enters their email
- A temporary signed login link is generated
- Clicking the link logs the user in automatically

#### 2) Custom login controller
- Handles link creation, validation, and authentication

#### 3) Tailwind CSS login page
- Modern UI for testing links
- Copy-to-clipboard functionality

#### 4) Routes and dashboard
- /login-link → login form
- /login/{user} → login via signed link
- /dashboard → protected route after login

---

## Step 1: Create Laravel 12 Project

```bash
composer create-project laravel/laravel PHP_Laravel12_Login_Link "12.*"
cd PHP_Laravel12_Login_Link
```

---

## Step 2: Install Required Packages

```bash
composer require spatie/laravel-login-link
composer require laravel/breeze --dev
php artisan breeze:install
npm install && npm run dev
php artisan migrate
```

> Breeze provides a login/registration scaffold which we will override using login links.

---

## Step 3: Publish Config and Views (Optional)

Publish config:

```bash
php artisan vendor:publish --tag="login-link-config"
```

Publish views:

```bash
php artisan vendor:publish --tag="login-link-views"
```

Edit config/login-link.php as needed:

```php
return [
    'allowed_environments' => ['local'],
    'allowed_hosts' => ['localhost'],
    'automatically_create_missing_users' => true,
    'user_model' => null, // default User model
    'redirect_route_name' => 'dashboard', 
    'login_link_controller' => \Spatie\LoginLink\Http\Controllers\LoginLinkController::class,
    'middleware' => ['web'],
];
```

---

## Step 4: Create Login Controller

```bash
php artisan make:controller LoginLinkController
```

Edit app/Http/Controllers/LoginLinkController.php:

```php
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
```
---

## Step 5: Configure Routes

Update routes/web.php:

```php
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginLinkController;
// use Spatie\LoginLink\Http\Controllers\LoginLinkController;


Route::get('/login-link', [LoginLinkController::class, 'showForm'])
    ->name('login-link.form');

Route::post('/login-link', [LoginLinkController::class, 'sendLink'])
    ->name('login-link.send');

Route::get('/login/{user}', [LoginLinkController::class, 'login'])
    ->middleware('signed')
    ->name('login-link.login');

Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard'); // <-- Load your dashboard.blade.php
})->name('dashboard');
```

---

## Step 6: Create Blade View for Login Page

Create file: resources/views/login-link/form.blade.php

```blade
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Magic Login</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #6366f1, #8b5cf6, #ec4899);
        }
    </style>

</head>

<body class="flex items-center justify-center min-h-screen px-4">

    <div class="w-full max-w-md">

        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl p-8">

            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Magic Login</h1>
                <p class="text-gray-500 text-sm mt-2">
                    Enter your email to generate a secure login link
                </p>
            </div>

            @if(session('success'))

            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-5">

                <p class="text-green-700 text-sm mb-2 font-medium">
                    Login link generated successfully
                </p>

                <div class="bg-white border rounded-md p-3 text-xs break-all text-gray-700" id="loginLink">
                    {{ session('success') }}
                </div>

                <button onclick="copyLink()"
                    class="mt-3 w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition">
                    Copy Login Link
                </button>

            </div>

            @endif


            @if($errors->any())

            <div class="bg-red-100 border border-red-200 text-red-700 p-3 rounded-lg mb-4 text-sm">
                {{ $errors->first() }}
            </div>

            @endif


            <form action="{{ route('login-link.send') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="text-sm text-gray-600">Email Address</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="you@example.com"
                        required
                        class="w-full mt-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>

                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition shadow">
                    Send Magic Login Link
                </button>

            </form>

            <p class="text-center text-xs text-gray-400 mt-6">
                Secure passwordless login using signed URLs
            </p>

        </div>

    </div>


    <script>
        function copyLink() {
            let link = document.getElementById("loginLink").innerText;
            navigator.clipboard.writeText(link);
            alert("Login link copied!");
        }
    </script>

</body>

</html>
```

---

## Step 7: Test the Login Link System

Run Laravel server:

```bash
php artisan serve
```

Visit:

```bash
http://localhost:8000/login-link
```

---

## Output

<img width="1915" height="1031" alt="Screenshot 2026-03-11 123736" src="https://github.com/user-attachments/assets/bcff1416-9458-4dd6-bd5e-0637f2b0b4a2" />

<img width="1919" height="1031" alt="Screenshot 2026-03-11 123746" src="https://github.com/user-attachments/assets/5b962147-dbda-4ad2-a44a-1f41166adc7c" />

<img width="1919" height="1027" alt="Screenshot 2026-03-11 123803" src="https://github.com/user-attachments/assets/7906e9fe-9aca-4e0b-be7a-31e416a05668" />

---

## Project Structure

```
PHP_Laravel12_Login_Link/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── LoginLinkController.php       <-- Your custom login controller
│   │   │   └── ProfileController.php
│   │   ├── Middleware/
│   ├── Models/
│   │   └── User.php                         <-- Default User model
│   ├── Providers/
│   └── ...
├── bootstrap/
│   └── app.php
├── config/
│   ├── app.php
│   ├── auth.php
│   └── login-link.php                        <-- Optional if using Spatie
├── database/
│   ├── migrations/
│   │   ├── 2014_10_12_000000_create_users_table.php
│   │   └── 2014_10_12_100000_create_password_resets_table.php
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── dashboard.blade.php             <-- Dashboard view
│       └── login-link/
│           └── form.blade.php              <-- Your magic login page
├── routes/
│   └── web.php                              <-- Routes for login-link & dashboard
├── storage/
├── tests/
├── vendor/
├── .env
├── artisan
├── composer.json
└── package.json
```

Your PHP_Laravel12_Login_Link Project is now ready!

