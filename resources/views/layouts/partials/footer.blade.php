<footer class="footer mt-auto py-3 bg-white position-relative shadow-sm" style="z-index: 10;">
    <div class="position-absolute top-0 start-0 w-100" 
         style="height: 3px; background: linear-gradient(90deg, #115B39 0%, #198754 50%, #ffc107 100%);">
    </div>

    <div class="container-fluid px-3 px-md-4 mt-1">
        <div class="row align-items-center justify-content-between flex-column flex-sm-row gap-2 gap-sm-0">
            
            {{-- Left: Copyright --}}
            <div class="col-auto text-center text-sm-start">
                <div class="small text-secondary fw-medium">
                    &copy; {{ date('Y') }} 
                    <span class="text-success fw-bold">Bank Sampah Buha</span>
                    <span class="text-warning fw-bold" style="color: #d39e00 !important;">Recycle Manado</span>
                </div>
                <div class="d-none d-md-block small text-muted mt-1">
                    Sistem Informasi Manajemen Daur Ulang
                </div>
            </div>

            {{-- Center: Eco Message (Desktop only) --}}
            <div class="col-auto d-none d-lg-flex align-items-center">
                <div class="eco-badge px-3 py-1 rounded-pill d-flex align-items-center">
                    <i class="fas fa-leaf text-success me-2 breathing-leaf"></i>
                    <span class="small fw-bold" style="color: #115B39; letter-spacing: 0.3px;">
                        Mari lestarikan Kota Manado bersama-sama
                    </span>
                </div>
            </div>

            {{-- Right: Version (Optional) --}}
            <div class="col-auto text-center text-sm-end d-none d-md-block">
                <span class="small text-muted">v1.0.0</span>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer {
        flex-shrink: 0;
        box-shadow: 0 -0.125rem 0.25rem rgba(0, 0, 0, 0.04) !important;
    }
    
    .eco-badge {
        background-color: rgba(25, 135, 84, 0.08);
        border: 1px solid rgba(25, 135, 84, 0.15);
    }
    
    .eco-badge:hover {
        background-color: rgba(25, 135, 84, 0.12);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    @keyframes breathe {
        0% { transform: scale(1); opacity: 0.8; }
        50% { transform: scale(1.15) rotate(5deg); opacity: 1; }
        100% { transform: scale(1); opacity: 0.8; }
    }
    
    .breathing-leaf {
        animation: breathe 3s infinite ease-in-out;
        display: inline-block;
    }
    
    @media (max-width: 767.98px) {
        .footer {
            padding: 0.75rem 0;
            font-size: 0.8rem;
        }
    }
</style>