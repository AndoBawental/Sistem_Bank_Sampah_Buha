
{{-- resources/views/layouts/partials/sidebar.blade.php --}}

{{-- Mobile Toggle Button --}}
<button class="btn btn-link d-md-none position-fixed top-0 start-0 m-3 z-3 shadow-lg" 
        id="sidebarToggleMobile" 
        style="z-index: 1050; background: #115B39; color: white; width: 42px; height: 42px; border-radius: 50%; display: none; align-items: center; justify-content: center;">
    <i class="fas fa-bars"></i>
</button>

{{-- Sidebar Overlay --}}
<div class="sidebar-overlay d-md-none" id="sidebarOverlay" style="display: none;"></div>

{{-- Sidebar --}}
<ul class="navbar-nav sidebar accordion shadow-lg" id="accordionSidebar" 
    style="background: linear-gradient(180deg, #115B39 0%, #073520 100%); min-height: 100vh; transition: all 0.3s;">

    @php
        $dashboardRoute = match(true) {
            auth()->user()->hasRole('admin') => 'admin.dashboard',
            auth()->user()->hasRole('gudang') => 'gudang.dashboard',
            auth()->user()->hasRole('produksi') => 'produksi.dashboard',
            auth()->user()->hasRole('penjualan') => 'penjualan.dashboard',
            default => 'dashboard'
        };
    @endphp

    {{-- Close Button Mobile --}}
    <div class="d-md-none text-end px-3 pt-3">
        <button class="btn btn-sm text-white" id="closeSidebarMobile" aria-label="Tutup menu">
            <i class="fas fa-times fa-lg"></i>
        </button>
    </div>

    {{-- Brand --}}
    <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center py-4 text-decoration-none"
       href="{{ route($dashboardRoute) }}">
        <div class="sidebar-brand-icon mb-1">
            <i class="fas fa-recycle fa-2x text-warning"></i>
        </div>
        <div class="sidebar-brand-text text-white fw-bold text-center mt-2">
            <div style="font-size: 1.15rem; letter-spacing: 0.5px;">Bank Sampah Buha</div>
            <div style="font-size: 0.75rem; letter-spacing: 0.5px; color: rgba(255, 255, 255, 0.75);">Recycle Manado</div>  
        </div>
    </a>

    <hr class="sidebar-divider opacity-25 mx-3">

    {{-- Dashboard --}}
    <li class="nav-item {{ request()->routeIs($dashboardRoute) ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-2 custom-hover"
           href="{{ route($dashboardRoute) }}">
            <div class="icon-box me-3"><i class="fas fa-tachometer-alt"></i></div>
            <span class="fw-semibold">Dashboard</span>
        </a>
    </li>

    {{-- ==================== GUDANG ==================== --}}
    @hasanyrole('admin|gudang')
    <div class="sidebar-heading text-white-50 small px-4 mt-3 mb-2 text-uppercase fw-bold" style="letter-spacing: 1px;">
        <i class="fas fa-warehouse me-1"></i> <span>Gudang</span>
    </div>
    
    <li class="nav-item {{ request()->routeIs('gudang.penerimaan*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" href="{{ route('gudang.penerimaan.index') }}">
            <div class="icon-box me-3"><i class="fas fa-truck-loading"></i></div>
            <span>Penerimaan Sampah</span>
        </a>
    </li>

    {{-- Sortir Sampah --}}
<li class="nav-item {{ request()->routeIs('gudang.sortir*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" 
       href="{{ route('gudang.sortir.index') }}">
        <div class="icon-box me-3"><i class="fas fa-filter"></i></div>
        <span>Sortir Sampah</span>
    </a>
</li>

    <li class="nav-item {{ request()->routeIs('gudang.stok*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" href="{{ route('gudang.stok.index') }}">
            <div class="icon-box me-3"><i class="fas fa-boxes"></i></div>
            <span>Stok Plastik Gudang</span>
        </a>
    </li>
    @endhasanyrole

    @role('admin')
    <li class="nav-item {{ request()->routeIs('gudang.supplier*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" href="{{ route('gudang.supplier.index') }}">
            <div class="icon-box me-3"><i class="fas fa-truck"></i></div>
            <span>Data Supplier</span>
        </a>
    </li>
    @endrole

    {{-- ==================== PRODUKSI ==================== --}}
    @hasanyrole('admin|produksi')
    <hr class="sidebar-divider opacity-25 mx-3 my-2">
    <div class="sidebar-heading text-white-50 small px-4 mt-2 mb-2 text-uppercase fw-bold" style="letter-spacing: 1px;">
        <i class="fas fa-industry me-1"></i> <span>Produksi</span>
    </div>

    <li class="nav-item {{ request()->routeIs('produksi.produksi') || request()->routeIs('produksi.create') || request()->routeIs('produksi.show') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" href="{{ route('produksi.produksi') }}">
            <div class="icon-box me-3"><i class="fas fa-cogs"></i></div>
            <span>Proses Produksi</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('produksi.stok*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" href="{{ route('produksi.stok.index') }}">
            <div class="icon-box me-3"><i class="fas fa-boxes"></i></div>
            <span>Stok Produk Gudang</span>
        </a>
    </li>
    @endhasanyrole

    {{-- ==================== DATA UTAMA ==================== --}}
    @role('admin')
    <hr class="sidebar-divider opacity-25 mx-3 my-2">
    <div class="sidebar-heading text-white-50 small px-4 mt-2 mb-2 text-uppercase fw-bold">
        <i class="fas fa-database me-1"></i> <span>Data Utama</span>
    </div>

    <li class="nav-item {{ request()->routeIs('data-utama.jenis-plastik*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" 
           href="{{ route('data-utama.jenis-plastik.index') }}">
            <div class="icon-box me-3"><i class="fas fa-tags"></i></div>
            <span>Jenis Plastik</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('data-utama.jenis-produk*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" 
           href="{{ route('data-utama.jenis-produk.index') }}">
            <div class="icon-box me-3"><i class="fas fa-box"></i></div>
            <span>Jenis Produk</span>
        </a>
    </li>
    @endrole

    {{-- ==================== PENJUALAN ==================== --}}
    @hasanyrole('admin|penjualan')
    <hr class="sidebar-divider opacity-25 mx-3 my-2">
    <div class="sidebar-heading text-white-50 small px-4 mt-2 mb-2 text-uppercase fw-bold" style="letter-spacing: 1px;">
        <i class="fas fa-shopping-cart me-1"></i> <span>Penjualan</span>
    </div>

    <li class="nav-item {{ request()->routeIs('penjualan.penjualan') || request()->routeIs('penjualan.create') || request()->routeIs('penjualan.show') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" 
           href="{{ route('penjualan.penjualan') }}">
            <div class="icon-box me-3"><i class="fas fa-cash-register"></i></div>
            <span>Transaksi Penjualan</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('penjualan.pembeli*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover"
           href="{{ route('penjualan.pembeli.index') }}">
            <div class="icon-box me-3"><i class="fas fa-users"></i></div>
            <span>Data Pembeli</span>
        </a>
    </li>
    @endhasanyrole

    {{-- ==================== LAPORAN ==================== --}}
    @if(auth()->user()->hasAnyRole(['admin', 'gudang', 'produksi', 'penjualan']))
    <hr class="sidebar-divider opacity-25 mx-3 my-2">
    <li class="nav-item mt-2">
        <a class="nav-link collapsed d-flex justify-content-between align-items-center py-2 px-3 rounded mx-3 custom-hover"
           href="#" data-bs-toggle="collapse" data-bs-target="#collapseLaporan" aria-expanded="false">
            <div class="d-flex align-items-center">
                <div class="icon-box me-3"><i class="fas fa-chart-pie"></i></div>
                <span>Laporan</span>
            </div>
            <i class="fas fa-chevron-down small transition-icon"></i>
        </a>
        <div class="collapse" id="collapseLaporan">
            <ul class="nav flex-column ms-3 mt-1">
                @hasanyrole('admin|gudang')
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 rounded" href="{{ route('laporan.penerimaan') }}">
                        <i class="fas fa-truck me-2"></i>Penerimaan
                    </a>
                </li>
                @endhasanyrole
                
                @hasanyrole('admin|produksi')
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 rounded" href="{{ route('laporan.produksi') }}">
                        <i class="fas fa-industry me-2"></i>Produksi
                    </a>
                </li>
                @endhasanyrole
                
                @hasanyrole('admin|penjualan')
                <li class="nav-item">
                    <a class="nav-link py-2 px-3 rounded" href="{{ route('laporan.penjualan') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Penjualan
                    </a>
                </li>
                @endhasanyrole
                
              
            </ul>
        </div>
    </li>
    @endif

    {{-- ==================== SISTEM ==================== --}}
    @role('admin')
    <hr class="sidebar-divider opacity-25 mx-3 my-2">
    <div class="sidebar-heading text-white-50 small px-4 mt-2 mb-2 text-uppercase fw-bold" style="letter-spacing: 1px;">
        <i class="fas fa-shield-alt me-1"></i> <span>Sistem</span>
    </div>

    <li class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" 
           href="{{ route('admin.users.index') }}">
            <div class="icon-box me-3"><i class="fas fa-users-cog"></i></div>
            <span>Kelola Pengguna</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" 
           href="{{ route('admin.roles.index') }}">
            <div class="icon-box me-3"><i class="fas fa-user-tag"></i></div>
            <span>Daftar Role</span>
        </a>
    </li>
    @endrole

    {{-- ==================== PROFILE & LOGOUT ==================== --}}
    <div class="flex-grow-1"></div>

    <div class="mx-3 mt-4 mb-4">
        <div class="glass-card p-3 rounded-4 shadow-sm text-center position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at top right, rgba(255,193,7,0.2), transparent 70%); pointer-events: none;"></div>
            
            <div class="d-flex align-items-center justify-content-center mb-3">
                <div class="avatar-circle bg-warning text-dark fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; font-size: 1.2rem;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
            
            <h6 class="text-white mb-1 fw-bold text-truncate">{{ auth()->user()->name }}</h6>
            <span class="badge bg-warning text-dark px-3 py-1 mb-3 rounded-pill" style="font-size: 0.75rem;">
                {{ ucfirst(auth()->user()->getRoleNames()->first() ?? 'User') }}
            </span>

            <hr class="opacity-25 mt-0 mb-3 text-white">

            <form action="{{ route('logout') }}" method="POST" class="d-grid">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill d-flex align-items-center justify-content-center logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i>Keluar
                </button>
            </form>
        </div>
    </div>
</ul>

<style>
    /* ========== SIDEBAR STYLES ========== */
    
    .sidebar {
        width: var(--sidebar-width, 280px);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        height: -webkit-fill-available;
        z-index: 1040;
        overflow-y: auto;
        overflow-x: hidden;
        transition: transform 0.3s ease-in-out;
        will-change: transform;
    }
    
    /* Tablet */
    @media (max-width: 1024px) {
        .sidebar {
            width: var(--sidebar-width-tablet, 240px);
        }
        .sidebar-brand-text div:first-child {
            font-size: 1rem !important;
        }
        .sidebar-brand-text div:last-child {
            font-size: 0.7rem !important;
        }
    }
    
    /* Mobile */
    @media (max-width: 767.98px) {
        .sidebar {
            transform: translateX(-100%);
            width: 280px;
            max-width: 85vw;
            z-index: 1050;
        }
        
        .sidebar.active {
            transform: translateX(0);
        }
        
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1045;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }
        
        .nav-link {
            padding-top: 12px !important;
            padding-bottom: 12px !important;
        }
        
        .sidebar-brand {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }
    }
    
    /* Small Mobile */
    @media (max-width: 480px) {
        .sidebar {
            width: 100%;
            max-width: 300px;
        }
        .sidebar.active {
            width: 85vw;
        }
    }
    
    /* Nav Links */
    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 0.5rem;
    }
    
    .icon-box {
        width: 25px;
        display: flex;
        justify-content: center;
        color: rgba(255, 255, 255, 0.6);
        transition: all 0.3s ease;
    }
    
    .custom-hover:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        transform: translateX(4px);
    }
    
    .custom-hover:hover .icon-box {
        color: #ffc107;
    }
    
    .nav-item.active .nav-link {
        background: rgba(255, 255, 255, 0.15);
        box-shadow: inset 3px 0 0 #ffc107;
        color: white;
        font-weight: 600;
    }
    
    .nav-item.active .icon-box {
        color: #ffc107;
    }
    
    /* Glass Card */
    .glass-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .logout-btn {
        transition: all 0.3s;
    }
    
    .logout-btn:hover {
        background-color: #ffc107;
        color: #000 !important;
        transform: translateY(-2px);
    }
    
    /* Scrollbar */
    .sidebar::-webkit-scrollbar {
        width: 5px;
    }
    
    .sidebar::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }
    
    .sidebar::-webkit-scrollbar-thumb:hover {
        background: #ffc107;
    }
    
    /* Toggle Arrow */
    .transition-icon {
        transition: transform 0.3s ease;
    }
    
    [aria-expanded="false"] .fa-chevron-down,
    .collapsed .fa-chevron-down {
        transform: rotate(-90deg);
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile Sidebar Toggle
        const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
        const closeSidebarMobile = document.getElementById('closeSidebarMobile');
        const sidebar = document.querySelector('.sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        // Function to open sidebar
        function openSidebar() {
            sidebar.classList.add('active');
            sidebarOverlay.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }
        
        // Function to close sidebar
        function closeSidebar() {
            sidebar.classList.remove('active');
            sidebarOverlay.style.display = 'none';
            document.body.style.overflow = ''; // Restore background scroll
        }
        
        // Toggle button click
        if (sidebarToggleMobile) {
            sidebarToggleMobile.addEventListener('click', function(e) {
                e.stopPropagation();
                openSidebar();
            });
        }
        
        // Close button click
        if (closeSidebarMobile) {
            closeSidebarMobile.addEventListener('click', function() {
                closeSidebar();
            });
        }
        
        // Overlay click
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                closeSidebar();
            });
        }
        
        // Close sidebar when clicking a nav link (mobile)
        const navLinks = document.querySelectorAll('.sidebar .nav-link:not([data-bs-toggle="collapse"])');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    // Don't close immediately, wait for navigation
                    setTimeout(closeSidebar, 300);
                }
            });
        });
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth >= 768) {
                    // Reset sidebar state on desktop
                    sidebar.classList.remove('active');
                    if (sidebarOverlay) {
                        sidebarOverlay.style.display = 'none';
                    }
                    document.body.style.overflow = '';
                }
            }, 250);
        });
        
        // Handle swipe gestures for mobile
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, {passive: true});
        
        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, {passive: true});
        
        function handleSwipe() {
            const swipeThreshold = 100;
            
            // Swipe right to open sidebar (from left edge)
            if (touchStartX < 30 && touchEndX - touchStartX > swipeThreshold) {
                if (window.innerWidth < 768 && !sidebar.classList.contains('active')) {
                    openSidebar();
                }
            }
            
            // Swipe left to close sidebar
            if (touchStartX > 200 && touchStartX - touchEndX > swipeThreshold) {
                if (window.innerWidth < 768 && sidebar.classList.contains('active')) {
                    closeSidebar();
                }
            }
        }
        
        // Desktop sidebar toggle (if you have a toggle button for desktop)
        const sidebarToggleDesktop = document.getElementById('sidebarToggle');
        
        if (sidebarToggleDesktop) {
            sidebarToggleDesktop.addEventListener('click', function() {
                document.body.classList.toggle('sidebar-toggled');
                sidebar.classList.toggle('toggled');
                
                const icon = this.querySelector('i');
                if (document.body.classList.contains('sidebar-toggled')) {
                    icon.classList.remove('fa-chevron-left');
                    icon.classList.add('fa-chevron-right');
                } else {
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-chevron-left');
                }
            });
        }
    });
</script>
@endpush