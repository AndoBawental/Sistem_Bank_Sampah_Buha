{{-- resources/views/layouts/partials/footer.blade.php --}}

<footer class="footer mt-auto py-3 bg-white position-relative shadow-sm" style="z-index: 10;">
    <div class="position-absolute top-0 start-0 w-100" 
         style="height: 3px; background: linear-gradient(90deg, #115B39 0%, #198754 50%, #ffc107 100%);">
    </div>

    <div class="container-fluid px-4 mt-1">
        <div class="row align-items-center justify-content-between flex-column flex-sm-row gap-3 gap-sm-0">
            
            {{-- Bagian Kiri: Hak Cipta & Info Sistem --}}
            <div class="col-auto text-center text-sm-start">
                <div class="small m-0 text-secondary fw-medium">
                    &copy; {{ date('Y') }} 
                    <span class="text-success fw-bold">Bank Sampah Buha</span>
                    <span class="text-warning fw-bold" style="color: #d39e00 !important;">Recycle Manado</span>. 
                    <span class="d-none d-md-inline ms-1 text-muted">
                        | Sistem Informasi Manajemen Daur Ulang
                    </span>
                </div>
            </div>

            {{-- Bagian Tengah: Pesan Eco dengan Efek Premium (Sembunyi di Layar Kecil) --}}
            <div class="col-auto d-none d-lg-block">
                <div class="eco-badge px-4 py-2 rounded-pill d-flex align-items-center transition-all">
                    <i class="fas fa-leaf text-success me-2 breathing-leaf"></i>
                    <span class="small fw-bold" style="color: #115B39; letter-spacing: 0.3px;">
                        Mari lestarikan Kota Manado bersama-sama
                    </span>
                </div>
            </div>

           
            
        </div>
    </div>
</footer>

<style>
    /* Mengatur Flexbox pada body agar footer selalu di bawah */
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
    }

    /* Pastikan tag <main> atau <div id="content"> kamu punya properti ini: 
       flex: 1 0 auto; 
    */

    .footer {
        flex-shrink: 0;
        box-shadow: 0 -0.125rem 0.25rem rgba(0, 0, 0, 0.04) !important;
    }
    
    /* Efek Transisi Umum */
    .transition-all {
        transition: all 0.3s ease-in-out;
    }

    /* Hover Effect untuk Link Bantuan/Panduan */
    .hover-success:hover {
        color: #198754 !important;
        transform: translateY(-1px);
    }

    /* Styling Badge Eco (Tengah) */
    .eco-badge {
        background-color: rgba(25, 135, 84, 0.08);
        border: 1px solid rgba(25, 135, 84, 0.15);
    }
    .eco-badge:hover {
        background-color: rgba(25, 135, 84, 0.12);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    /* Animasi Daun Bernapas (Breathing Effect) */
    @keyframes breathe {
        0% { transform: scale(1); opacity: 0.8; }
        50% { transform: scale(1.15) rotate(5deg); opacity: 1; text-shadow: 0 0 5px rgba(25,135,84,0.4); }
        100% { transform: scale(1); opacity: 0.8; }
    }
    
    .breathing-leaf {
        animation: breathe 3s infinite ease-in-out;
        display: inline-block;
    }
</style>