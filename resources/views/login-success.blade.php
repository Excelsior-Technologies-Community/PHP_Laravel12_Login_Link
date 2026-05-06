<!DOCTYPE html>
<html>

<head>
    <title>Login Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-green-50 flex items-center justify-center h-screen">

    <div class="bg-white p-10 rounded-xl shadow text-center max-w-md">

        <div class="text-green-600 text-5xl mb-4">
            ✔
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            Login Successful 🎉
        </h1>

        <p class="text-gray-600 mb-6">
            You have successfully logged in using Magic Login Link
        </p>

        <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
            Go to Dashboard
        </a>

    </div>

</body>

</html>