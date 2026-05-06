<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-10 rounded-xl shadow text-center">

        <h1 class="text-2xl font-bold text-green-600 mb-3">
            Welcome to Dashboard 🎉
        </h1>

        <p class="text-gray-600 mb-5">
            You are successfully logged in using Magic Link
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-red-500 text-white px-5 py-2 rounded">
                Logout
            </button>
        </form>

    </div>

</body>

</html>