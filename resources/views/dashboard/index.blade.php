<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow">

        <h1 class="text-2xl font-bold mb-4">
            Dashboard Umum
        </h1>

        <p class="mb-4">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>
        </p>

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-red-500 text-white px-4 py-2 rounded">
                Logout
            </button>
        </form>

    </div>

</body>
</html>