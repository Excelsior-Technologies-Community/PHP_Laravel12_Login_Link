<!DOCTYPE html>
<html>
<head>
    <title>Login Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">

    <div class="bg-white p-10 rounded-2xl shadow-lg text-center max-w-sm w-full">
        <div class="text-green-500 text-6xl mb-4">
            ✓
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            Login Successful 🎉
        </h1>

        <p class="text-gray-600 mb-6">
            You have successfully logged in. Redirecting to your dashboard in 
            <span id="countdown" class="font-bold text-indigo-600">3</span> seconds...
        </p>

        <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
            <div id="progress-bar" class="bg-indigo-600 h-2 rounded-full" style="width: 0%"></div>
        </div>

        <a href="{{ route('dashboard') }}" class="block bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 transition font-semibold">
            Go to Dashboard Now
        </a>
    </div>

    <script>
        let seconds = 3;
        const countdownEl = document.getElementById('countdown');
        const progressBar = document.getElementById('progress-bar');

        const interval = setInterval(() => {
            seconds--;
            countdownEl.innerText = seconds;
            progressBar.style.width = ((3 - seconds) / 3) * 100 + "%";

            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = "{{ route('dashboard') }}";
            }
        }, 1000);
    </script>
</body>
</html>