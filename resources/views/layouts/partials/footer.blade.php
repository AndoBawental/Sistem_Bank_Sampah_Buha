<footer class="footer mt-auto py-4 bg-white border-top shadow-sm">
    <div class="container-fluid px-4">
        <div class="row align-items-center justify-content-between flex-column flex-sm-row">
            
            {{-- Bagian Kiri: Hak Cipta --}}
            <div class="col-auto">
                <div class="small m-0 text-muted">
                    &copy; {{ date('Y') }} 
                    <span class="text-success fw-bold">Bank Sampah Buha </span>
                    <span class="text-dark fw-bold">Recycle Manado</span>. 
                    Semua Hak Dilindungi.
                </div>
            </div>

            {{-- Bagian Tengah: Pesan Eco (Hanya muncul di Layar Besar) --}}
            <div class="col-auto d-none d-lg-block">
                <span class="badge rounded-pill bg-light text-success border border-success-subtle px-3 py-2">
                    <i class="fas fa-seedling me-1"></i> Mari lestarikan Kota Manado bersama-sama
                </span>
            </div>

           
        </div>
    </div>
</footer>

<style>
    /* CSS Tambahan agar footer tetap di bawah dan punya efek hover */
    .footer {
        background-color: #ffffff;
    }
    
    .hover-success:hover {
        color: #198754 !important;
        transition: color 0.3s ease;
    }

    /* Memastikan footer tidak "mengambang" jika konten sedikit */
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    main {
        flex: 1 0 auto;
    }

    .footer {
        flex-shrink: 0;
    }
</style>