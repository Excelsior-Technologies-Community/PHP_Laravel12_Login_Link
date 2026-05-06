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

            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Magic Login</h1>
                <p class="text-gray-500 text-sm mt-2">
                    Enter your email to generate a secure login link
                </p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-5">

                    <p class="text-green-700 text-sm font-medium mb-2">
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

            <!-- Error Message -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-200 text-red-700 p-3 rounded-lg mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
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

    <!-- JS -->
    <script>
        function copyLink() {
            const linkBox = document.getElementById("loginLink");

            if (!linkBox) {
                alert("No link available to copy!");
                return;
            }

            const link = linkBox.innerText.trim();

            navigator.clipboard.writeText(link)
                .then(() => {
                    alert("Login link copied successfully!");
                })
                .catch(() => {
                    alert("Failed to copy link!");
                });
        }
    </script>

</body>

</html>