<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produksi Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow">

        <h1 class="text-2xl font-bold text-yellow-600 mb-4">
            Dashboard Produksi
        </h1>

        <p>Halo Tim Produksi, {{ auth()->user()->name }}</p>

        <ul class="mt-4 list-disc pl-5">
            <li>Input Produksi</li>
            <li>Monitoring Hasil Produksi</li>
        </ul>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button class="bg-red-500 text-white px-4 py-2 rounded">
                Logout
            </button>
        </form>

    </div>

</body>
</html>