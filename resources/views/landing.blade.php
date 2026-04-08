<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah Buha</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-green-600 text-white p-4">
        <div class="container mx-auto flex justify-between">
            <h1 class="font-bold text-lg">Bank Sampah Buha</h1>
            <a href="/login" class="bg-white text-green-600 px-4 py-2 rounded">
                Login
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="text-center py-20 bg-green-500 text-white">
        <h1 class="text-4xl font-bold mb-4">
            Sistem Informasi Bank Sampah Buha
        </h1>
        <p class="text-lg">
            Mengelola limbah plastik menjadi bernilai ekonomi
        </p>
    </section>

    <!-- Tentang -->
    <section class="container mx-auto py-12">
        <h2 class="text-2xl font-bold mb-4 text-center">
            Tentang Sistem
        </h2>
        <p class="text-center text-gray-700 max-w-2xl mx-auto">
            Sistem ini dibuat untuk membantu pengelolaan bank sampah,
            mulai dari penerimaan sampah, pengelolaan stok, produksi,
            hingga penjualan hasil daur ulang.
        </p>
    </section>

    <!-- Fitur -->
    <section class="bg-white py-12">
        <div class="container mx-auto">
            <h2 class="text-2xl font-bold text-center mb-8">
                Fitur Utama
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-gray-100 rounded shadow">
                    <h3 class="font-bold mb-2">Penerimaan Sampah</h3>
                    <p>Input dan pencatatan sampah dari supplier</p>
                </div>

                <div class="p-6 bg-gray-100 rounded shadow">
                    <h3 class="font-bold mb-2">Manajemen Stok</h3>
                    <p>Kontrol stok berdasarkan jenis plastik</p>
                </div>

                <div class="p-6 bg-gray-100 rounded shadow">
                    <h3 class="font-bold mb-2">Produksi & Penjualan</h3>
                    <p>Kelola hasil produksi dan transaksi penjualan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-green-600 text-white text-center p-4 mt-10">
        <p>© {{ date('Y') }} Bank Sampah Buha</p>
    </footer>

</body>
</html>