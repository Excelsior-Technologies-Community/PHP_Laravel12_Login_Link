<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-4xl mx-auto">
        <div class="bg-white p-8 rounded-xl shadow mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Welcome, {{ auth()->user()->name }}! 🎉</h1>
            <p class="text-gray-600">You are successfully logged in using Magic Link.</p>
            
            <div class="mt-6 flex gap-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-red-500 text-white px-5 py-2 rounded hover:bg-red-600 transition">Logout</button>
                </form>
            </div>
        </div>

        <div class="bg-white p-8 rounded-xl shadow">
            <h2 class="text-xl font-bold mb-4">Recent Login Activity</h2>
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 border">IP Address</th>
                        <th class="p-3 border">Status</th>
                        <th class="p-3 border">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\LoginAttempt::where('email', auth()->user()->email)->latest()->limit(5)->get() as $attempt)
                        <tr>
                            <td class="p-3 border">{{ $attempt->ip_address }}</td>
                            <td class="p-3 border">
                                <span class="{{ $attempt->status == 'success' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ucfirst($attempt->status) }}
                                </span>
                            </td>
                            <td class="p-3 border">{{ $attempt->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>