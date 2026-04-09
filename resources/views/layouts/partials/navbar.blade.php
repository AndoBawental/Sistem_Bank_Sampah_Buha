<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm px-3">

    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3 text-success">
        <i class="fa fa-bars"></i>
    </button>

    <h1 class="h4 mb-0 text-gray-800 d-none d-sm-inline-block fw-bold">
        @yield('page-title', 'Dashboard')
    </h1>

    <ul class="navbar-nav ms-auto align-items-center">

       

        <div class="topbar-divider d-none d-sm-block mx-3 border-end" style="height: 2rem;"></div>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="text-end me-2 d-none d-lg-inline">
                    <span class="d-block small fw-bold text-dark">{{ auth()->user()->name }}</span>
                    <span class="d-block small text-muted" style="font-size: 0.7rem;">Pahlawan Lingkungan</span>
                </div>
                <div class="position-relative">
                    <img class="img-profile rounded-circle border border-2 border-success" 
                         src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=198754&color=fff" 
                         width="40" height="40">
                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in border-0" aria-labelledby="userDropdown">
                <div class="dropdown-header text-uppercase small fw-bold">Pengaturan Akun</div>
                
                <li>
                    <a class="dropdown-item py-2" href="{{ route('dashboard') }}">
                        <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                        Profil Saya
                    </a>
                </li>
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

</nav>

<style>
    /* CSS Tambahan untuk mempermanis */
    .bg-success-soft {
        background-color: rgba(25, 135, 84, 0.1);
    }
    .topbar .nav-item .nav-link {
        height: 4.375rem;
        display: flex;
        align-items: center;
        padding: 0 0.75rem;
    }
    .dropdown-item:active {
        background-color: #198754;
    }
    .img-profile {
        object-fit: cover;
    }
</style>