<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Gudang</title>
</head>
<body>

    <h1>Dashboard Gudang</h1>

    <p>Selamat datang, {{ auth()->user()->name }}</p>

    <hr>

    <h3>Menu Gudang</h3>
    <ul>
        <li><a href="#">Data Stok</a></li>
        <li><a href="#">Penerimaan Barang</a></li>
        <li><a href="#">Distribusi Barang</a></li>
    </ul>

    <hr>

    <!-- Logout -->
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>