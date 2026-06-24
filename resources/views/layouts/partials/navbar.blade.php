{{-- resources/views/layouts/partials/navbar.blade.php --}}

<nav class="navbar navbar-expand navbar-light bg-white topbar shadow-sm" style="z-index: 1030;">
    <div class="container-fluid px-3">
        
        {{-- Mobile Sidebar Toggle --}}
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-2 text-success p-2" 
                style="width: 40px; height: 40px;" aria-label="Toggle sidebar">
            <i class="fa fa-bars"></i>
        </button>

        {{-- Page Title --}}
        <h1 class="h5 mb-0 text-gray-800 d-none d-sm-inline-block fw-bold text-truncate" style="max-width: 300px;">
            @yield('page-title', 'Dashboard')
        </h1>

        {{-- Spacer --}}
        <div class="flex-grow-1"></div>

        {{-- Right Side --}}
        <ul class="navbar-nav ms-auto align-items-center flex-row gap-2">
            
            {{-- Divider --}}
            <div class="topbar-divider d-none d-sm-block mx-2 border-end" style="height: 2rem;"></div>

            {{-- User Dropdown --}}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center p-1" href="#" id="userDropdown" role="button"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="text-end me-2 d-none d-lg-inline">
                        <span class="d-block small fw-bold text-dark">{{ auth()->user()->name }}</span>
                        <span class="d-block small text-muted" style="font-size: 0.7rem;">Pahlawan Lingkungan</span>
                    </div>
                    <div class="position-relative flex-shrink-0">
                        <img class="img-profile rounded-circle border border-2 border-success" 
                             src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=198754&color=fff&size=80" 
                             width="36" height="36" 
                             alt="Avatar {{ auth()->user()->name }}"
                             loading="lazy">
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle" 
                              style="width: 10px; height: 10px;"></span>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userDropdown" style="min-width: 200px;">
                    <li>
                        <a class="dropdown-item py-2" href="#">
                            <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>
                            Settings
                        </a>
                    </li>
                    
                    <li><hr class="dropdown-divider"></li>
                    
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger fw-bold">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw me-2"></i>
                                Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<style>
    /* ✅ PERBAIKAN: Navbar tidak fixed di desktop, hanya fixed di mobile */
    .topbar {
        height: var(--header-height, 60px);
    }
    
    .topbar .nav-link {
        height: 100%;
        display: flex;
        align-items: center;
        padding: 0 0.5rem;
        border-radius: 0.5rem;
    }
    
    .topbar .nav-link:hover {
        background: rgba(25, 135, 84, 0.05);
    }
    
    .dropdown-item:active {
        background-color: #198754;
    }
    
    .img-profile {
        object-fit: cover;
    }
    
    /* ✅ Mobile: Navbar fixed di atas */
    @media (max-width: 767.98px) {
        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }
        
        .topbar .dropdown-menu {
            position: fixed;
            top: 60px;
            right: 10px;
            left: auto;
            width: auto;
        }
    }
    
    @media (max-width: 480px) {
        .topbar h1 {
            font-size: 0.9rem !important;
            max-width: 180px;
        }
    }
</style>

<script>
    // Connect sidebar toggle in navbar to mobile sidebar
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggleTop = document.getElementById('sidebarToggleTop');
        if (sidebarToggleTop) {
            sidebarToggleTop.addEventListener('click', function() {
                const sidebar = document.querySelector('.sidebar');
                const sidebarOverlay = document.getElementById('sidebarOverlay');
                
                if (sidebar && window.innerWidth < 768) {
                    sidebar.classList.add('active');
                    if (sidebarOverlay) sidebarOverlay.style.display = 'block';
                    document.body.classList.add('sidebar-open');
                }
            });
        }
    });
</script>