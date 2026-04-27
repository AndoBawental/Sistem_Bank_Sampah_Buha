{{-- resources/views/layouts/partials/sidebar.blade.php --}}

<ul class="navbar-nav sidebar accordion shadow-lg" id="accordionSidebar" 
    style="background: linear-gradient(180deg, #115B39 0%, #073520 100%); min-height: 100vh; transition: all 0.3s;">

    @php
        $dashboardRoute = 'dashboard';
        if(auth()->user()->hasRole('admin')) {
            $dashboardRoute = 'admin.dashboard';
        } elseif(auth()->user()->hasRole('gudang')) {
            $dashboardRoute = 'gudang.dashboard';
        } elseif(auth()->user()->hasRole('produksi')) {
            $dashboardRoute = 'produksi.dashboard';
        } elseif(auth()->user()->hasRole('penjualan')) {
            $dashboardRoute = 'penjualan.dashboard';
        }
    @endphp

    <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center py-4 text-decoration-none"
       href="{{ route($dashboardRoute) }}">
        <div class="sidebar-brand-icon mb-1 premium-icon">
            <i class="fas fa-recycle fa-2x text-warning"></i>
        </div>
        <div class="sidebar-brand-text text-white fw-bold text-center mt-2">
            <div style="font-size: 1.15rem; letter-spacing: 0.5px;">Bank Sampah Buha</div>
            <div class="text-warning" style="font-size: 0.8rem; letter-spacing: 1.5px; opacity: 0.9;">RECYCLE MANADO</div>
        </div>
    </a>

    <hr class="sidebar-divider opacity-25 mx-3">

    {{-- Dashboard - Semua Role --}}
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
        <i class="fas fa-warehouse me-1"></i> Gudang
    </div>
    
    <li class="nav-item {{ request()->routeIs('gudang.penerimaan*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" href="{{ route('gudang.penerimaan.index') }}">
            <div class="icon-box me-3"><i class="fas fa-truck-loading"></i></div>
            <span>Penerimaan Sampah</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('gudang.sortir*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" href="{{ route('gudang.sortir.index') }}">
            <div class="icon-box me-3"><i class="fas fa-filter"></i></div>
            <span>Sortir Sampah</span>
            @php
                $pendingSortir = \App\Models\Penerimaan::whereIn('status_sortir', ['Belum', 'Proses'])->count();
            @endphp
            @if($pendingSortir > 0)
                <span class="badge bg-warning text-dark ms-2 rounded-pill">{{ $pendingSortir }}</span>
            @endif
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('gudang.stok*') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" href="{{ route('gudang.stok.index') }}">
            <div class="icon-box me-3"><i class="fas fa-boxes"></i></div>
            <span>Stok Plastik Gudang</span>
        </a>
    </li>
    @endhasanyrole

    {{-- Supplier hanya untuk Admin --}}
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
        <i class="fas fa-industry me-1"></i> Produksi
    </div>

    {{-- Proses Produksi --}}
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

    {{-- ==================== DATA UTAMA (Admin Only) ==================== --}}
    @role('admin')
    <hr class="sidebar-divider opacity-25 mx-3 my-2">
    <div class="sidebar-heading text-white-50 small px-4 mt-2 mb-2 text-uppercase fw-bold">
        <i class="fas fa-database me-1"></i> Data Utama
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
        <i class="fas fa-shopping-cart me-1"></i> Penjualan
    </div>

    {{-- Transaksi Penjualan --}}
<li class="nav-item {{ request()->routeIs('penjualan.penjualan') || request()->routeIs('penjualan.create') || request()->routeIs('penjualan.show') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover" 
       href="{{ route('penjualan.penjualan') }}">
        <div class="icon-box me-3"><i class="fas fa-cash-register"></i></div>
        <span>Transaksi Penjualan</span>
    </a>
</li>
    @endhasanyrole

    @hasanyrole('admin|penjualan')
<li class="nav-item {{ request()->routeIs('penjualan.pembeli*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-2 px-3 rounded mx-3 mb-1 custom-hover"
       href="{{ route('penjualan.pembeli.index') }}">
        <div class="icon-box me-3"><i class="fas fa-users"></i></div>
        <span>Data Pembeli</span>
    </a>
</li>
@endhasanyrole

    {{-- ==================== LAPORAN (Sesuai Role) ==================== --}}
    @if(auth()->user()->hasAnyRole(['admin', 'gudang', 'produksi', 'penjualan']))
    <hr class="sidebar-divider opacity-25 mx-3 my-2">
    <li class="nav-item mt-2">
    <a class="nav-link collapsed d-flex justify-content-between align-items-center py-2 px-3 rounded mx-3 custom-hover"
       href="#" data-bs-toggle="collapse" data-bs-target="#collapseLaporan">
        <div class="d-flex align-items-center">
            <div class="icon-box me-3"><i class="fas fa-chart-pie"></i></div>
            <span>Laporan</span>
        </div>
        <i class="fas fa-chevron-down small"></i>
    </a>
    <div class="collapse" id="collapseLaporan">
        <ul class="nav flex-column ms-3 mt-1">
            {{-- Admin lihat semua laporan --}}
            @role('admin')
            <li class="nav-item">
                <a class="nav-link py-2 px-3 rounded" href="{{ route('laporan.penerimaan') }}">
                    <i class="fas fa-truck me-2"></i>Penerimaan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-2 px-3 rounded" href="{{ route('laporan.produksi') }}">
                    <i class="fas fa-industry me-2"></i>Produksi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-2 px-3 rounded" href="{{ route('laporan.penjualan') }}">
                    <i class="fas fa-shopping-cart me-2"></i>Penjualan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-2 px-3 rounded" href="{{ route('laporan.stok') }}">
                    <i class="fas fa-boxes me-2"></i>Stok
                </a>
            </li>
            @endrole

            {{-- Gudang hanya lihat laporan Penerimaan --}}
            @role('gudang')
            <li class="nav-item">
                <a class="nav-link py-2 px-3 rounded" href="{{ route('laporan.penerimaan') }}">
                    <i class="fas fa-truck me-2"></i>Penerimaan
                </a>
            </li>
            @endrole

            {{-- Produksi hanya lihat laporan Produksi --}}
            @role('produksi')
            <li class="nav-item">
                <a class="nav-link py-2 px-3 rounded" href="{{ route('laporan.produksi') }}">
                    <i class="fas fa-industry me-2"></i>Produksi
                </a>
            </li>
            @endrole

            {{-- Penjualan hanya lihat laporan Penjualan --}}
            @role('penjualan')
            <li class="nav-item">
                <a class="nav-link py-2 px-3 rounded" href="{{ route('laporan.penjualan') }}">
                    <i class="fas fa-shopping-cart me-2"></i>Penjualan
                </a>
            </li>
            @endrole
        </ul>
    </div>
    </li>
    @endif

    {{-- ==================== SISTEM (Admin Only) ==================== --}}
    @role('admin')
    <hr class="sidebar-divider opacity-25 mx-3 my-2">
    <div class="sidebar-heading text-white-50 small px-4 mt-2 mb-2 text-uppercase fw-bold" style="letter-spacing: 1px;">
        <i class="fas fa-shield-alt me-1"></i> Sistem
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
                    {{ substr(auth()->user()->name, 0, 1) }}
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
/* Base Colors & Typography */
.sidebar .nav-link {
    color: rgba(255, 255, 255, 0.75);
    font-size: 0.9rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Icon Containers for consistent alignment */
.icon-box {
    width: 25px;
    display: flex;
    justify-content: center;
    color: rgba(255, 255, 255, 0.6);
    transition: all 0.3s ease;
}

/* Hover & Active States (Glassmorphism) */
.custom-hover:hover {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
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

/* Sub-menus */
.glass-menu {
    background: rgba(0, 0, 0, 0.2);
    border-left: 1px solid rgba(255, 255, 255, 0.1);
}

.collapse-item {
    display: flex;
    align-items: center;
    padding: 8px 16px;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.2s;
}

.collapse-item:hover {
    color: #ffc107;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 6px;
}

/* Premium Card (Profile) */
.glass-card {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(10px);
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

/* Scrollbar Styling */
.sidebar {
    overflow-y: auto;
    overflow-x: hidden;
}
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

/* Toggle Arrow Animation */
.transition-icon i {
    transition: transform 0.3s ease;
}
.collapsed .fa-chevron-down {
    transform: rotate(-90deg);
}
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
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