<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Recycle Manado') | Bank Sampah Buha Recycle Manado</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Memastikan layout memenuhi tinggi layar */
        body, html {
            height: 100%;
            overflow-x: hidden;
        }
        #wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        #main-content {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        /* Penyesuaian konten agar tidak tertumpuk */
        .page-content {
            flex: 1;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-light">

<div id="wrapper">

    {{-- Sidebar --}}
    {{-- Pastikan di dalam file sidebar.blade.php menggunakan class yang responsif --}}
    @include('layouts.partials.sidebar')

    {{-- Main Area --}}
    <div id="main-content">

        {{-- Navbar --}}
        @include('layouts.partials.navbar')

        {{-- Content --}}
        <main class="page-content container-fluid p-3 p-md-4">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('layouts.partials.footer')

    </div>

</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')

</body>
</html>