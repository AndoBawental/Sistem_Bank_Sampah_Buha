<ul class="navbar-nav sidebar accordion shadow-lg" id="accordionSidebar" 
    style="background: linear-gradient(180deg, #198754 0%, #0b3d2e 100%);
           min-height: 100vh;">

    <!-- BRAND -->
    <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center py-4 text-decoration-none"
       href="{{ url('/dashboard') }}">
        <div class="sidebar-brand-icon mb-2">
            <i class="fas fa-recycle fa-2x text-warning"></i>
        </div>
        <div class="sidebar-brand-text text-white fw-bold text-center">
            <div style="font-size: 1.1rem;">Bank Sampah Buha</div>
            <div class="text-warning" style="letter-spacing: 1px;">Recycle Manado</div>
        </div>
    </a>

    <hr class="sidebar-divider opacity-25">

    <!-- DASHBOARD -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link d-flex align-items-center py-3 px-3 rounded mx-2 hover-effect"
           href="{{ route('dashboard') }}">
            <i class="fas fa-leaf text-warning me-2"></i>
            <span class="fw-semibold">Ringkasan Hijau</span>
        </a>
    </li>

    <hr class="sidebar-divider opacity-25">

    <!-- SECTION -->
    <div class="sidebar-heading text-white-50 small px-3">
        Manajemen Sampah
    </div>

    <!-- PENJEMPUTAN -->
    <li class="nav-item">
        <a class="nav-link collapsed d-flex justify-content-between align-items-center px-3 hover-effect"
           href="#" data-bs-toggle="collapse" data-bs-target="#collapsePickup">
            <div>
                <i class="fas fa-truck-moving me-2"></i>
                <span>Penjemputan</span>
            </div>
            <i class="fas fa-chevron-down small"></i>
        </a>

        <div id="collapsePickup" class="collapse" data-bs-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded shadow-sm mx-2">
                <a class="collapse-item" href="#">📅 Jadwal Pickup</a>
                <a class="collapse-item" href="#">♻️ Riwayat Setoran</a>
            </div>
        </div>
    </li>

    <!-- EDUKASI -->
    <li class="nav-item">
        <a class="nav-link collapsed d-flex justify-content-between align-items-center px-3 hover-effect"
           href="#" data-bs-toggle="collapse" data-bs-target="#collapseEdu">
            <div>
                <i class="fas fa-book-open me-2"></i>
                <span>Edukasi Lingkungan</span>
            </div>
            <i class="fas fa-chevron-down small"></i>
        </a>

        <div id="collapseEdu" class="collapse" data-bs-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded shadow-sm mx-2">
                <a class="collapse-item" href="#">🌱 Tips Daur Ulang</a>
                <a class="collapse-item" href="#">🗂️ Jenis Sampah</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider opacity-25">

    <!-- AKUN -->
    <div class="sidebar-heading text-white-50 small px-3">
        Akun & Poin
    </div>

    <li class="nav-item">
        <a class="nav-link d-flex align-items-center px-3 hover-effect" href="#">
            <i class="fas fa-coins text-warning me-2"></i>
            <span>Tukar Poin</span>
        </a>
    </li>

    <!-- TOGGLE -->
    <div class="text-center mt-4">
        <button class="rounded-circle border-0 p-2" id="sidebarToggle"
            style="background-color: rgba(255,255,255,0.15);">
            <i class="fas fa-chevron-left text-white"></i>
        </button>
    </div>

    <!-- CARD -->
    <div class="mx-3 mt-4 p-3 rounded shadow-sm text-center"
         style="background: rgba(255,255,255,0.1); color: white;">
        <p class="small mb-2">🌍 Terima kasih sudah menjaga bumi hari ini!</p>
        <a class="btn btn-warning btn-sm w-100 fw-bold shadow-sm" href="#">
            Donasi Sekarang
        </a>
    </div>

</ul>

<!-- STYLE TAMBAHAN -->
<style>
.hover-effect {
    transition: all 0.3s ease;
}

.hover-effect:hover {
    background-color: rgba(255,255,255,0.1);
    transform: translateX(5px);
}

.nav-item.active .nav-link {
    background-color: rgba(255,255,255,0.2);
    border-left: 4px solid #ffc107;
}

.collapse-item {
    display: block;
    padding: 8px 16px;
    color: #333;
    transition: 0.2s;
}

.collapse-item:hover {
    background-color: #198754;
    color: white;
    border-radius: 5px;
}
</style>