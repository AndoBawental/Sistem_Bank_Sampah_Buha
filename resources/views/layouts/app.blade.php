<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Recycle Manado') | Bank Sampah Buha Recycle Manado</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* ========== RESET & BASE ========== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body, html {
            height: 100%;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* ========== WRAPPER ========== */
        #wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
            position: relative;
        }
        
        /* ========== MAIN CONTENT AREA ========== */
        #main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            width: 100%;
        }
        
        /* ========== PAGE CONTENT ========== */
        .page-content {
            flex: 1;
            padding: 1.5rem;
            transition: padding 0.3s ease;
        }
        
        /* ========== DESKTOP (> 1024px) ========== */
        @media (min-width: 1025px) {
            #main-content {
                margin-left: 280px;
                width: calc(100% - 280px);
            }
            
            .page-content {
                padding: 2rem;
            }
        }
        
        /* ========== TABLET (768px - 1024px) ========== */
        @media (min-width: 768px) and (max-width: 1024px) {
            #main-content {
                margin-left: 240px;
                width: calc(100% - 240px);
            }
            
            .page-content {
                padding: 1.5rem;
            }
        }
        
        /* ========== MOBILE & SMALL TABLET (< 768px) ========== */
        @media (max-width: 767.98px) {
            #main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding-top: 60px; /* Space for mobile toggle button */
            }
            
            .page-content {
                padding: 1rem;
            }
            
            /* Prevent body scroll when sidebar is open */
            body.sidebar-open {
                overflow: hidden;
            }
        }
        
        /* ========== SMALL MOBILE (< 480px) ========== */
        @media (max-width: 480px) {
            .page-content {
                padding: 0.75rem;
            }
        }
        
        /* ========== UTILITY CLASSES ========== */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Smooth scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    
    @stack('styles')
</head>

<body class="bg-light">

<div id="wrapper">

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Main Content Area --}}
    <div id="main-content">

        {{-- Navbar --}}
        @include('layouts.partials.navbar')

        {{-- Content --}}
        <main class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        @include('layouts.partials.footer')

    </div>

</div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- App JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Responsive Sidebar Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========== MOBILE SIDEBAR HANDLER ==========
            const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
            const closeSidebarMobile = document.getElementById('closeSidebarMobile');
            const sidebar = document.querySelector('.sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('main-content');
            
            // Show mobile toggle button only on mobile
            function updateToggleButtonVisibility() {
                if (sidebarToggleMobile) {
                    if (window.innerWidth < 768) {
                        sidebarToggleMobile.style.display = 'flex';
                    } else {
                        sidebarToggleMobile.style.display = 'none';
                        // Ensure sidebar is visible on desktop
                        if (sidebar) {
                            sidebar.classList.remove('active');
                            sidebar.style.transform = '';
                        }
                        if (sidebarOverlay) {
                            sidebarOverlay.style.display = 'none';
                        }
                        document.body.classList.remove('sidebar-open');
                    }
                }
            }
            
            // Open sidebar
            function openSidebar() {
                if (sidebar) {
                    sidebar.classList.add('active');
                    sidebar.style.transform = 'translateX(0)';
                }
                if (sidebarOverlay) {
                    sidebarOverlay.style.display = 'block';
                }
                document.body.classList.add('sidebar-open');
            }
            
            // Close sidebar
            function closeSidebar() {
                if (sidebar) {
                    sidebar.classList.remove('active');
                    sidebar.style.transform = 'translateX(-100%)';
                }
                if (sidebarOverlay) {
                    sidebarOverlay.style.display = 'none';
                }
                document.body.classList.remove('sidebar-open');
            }
            
            // Toggle button click
            if (sidebarToggleMobile) {
                sidebarToggleMobile.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    openSidebar();
                });
            }
            
            // Close button click
            if (closeSidebarMobile) {
                closeSidebarMobile.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeSidebar();
                });
            }
            
            // Overlay click to close
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    closeSidebar();
                });
            }
            
            // Close sidebar when clicking nav links (mobile only)
            document.querySelectorAll('.sidebar .nav-link:not([data-bs-toggle="collapse"])').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        setTimeout(closeSidebar, 200);
                    }
                });
            });
            
            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    updateToggleButtonVisibility();
                    
                    // Close sidebar if switching to desktop
                    if (window.innerWidth >= 768) {
                        closeSidebar();
                    }
                }, 250);
            });
            
            // Swipe gestures for mobile
            let touchStartX = 0;
            let touchStartY = 0;
            let touchEndX = 0;
            
            document.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
                touchStartY = e.changedTouches[0].screenY;
            }, { passive: true });
            
            document.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                const touchEndY = e.changedTouches[0].screenY;
                handleSwipe(touchEndY);
            }, { passive: true });
            
            function handleSwipe(touchEndY) {
                // Only handle horizontal swipes
                const verticalDiff = Math.abs(touchStartY - touchEndY);
                const horizontalDiff = touchEndX - touchStartX;
                
                if (verticalDiff < Math.abs(horizontalDiff)) {
                    const swipeThreshold = 80;
                    
                    // Swipe right to open (from left edge)
                    if (touchStartX < 40 && horizontalDiff > swipeThreshold) {
                        if (window.innerWidth < 768) {
                            openSidebar();
                        }
                    }
                    
                    // Swipe left to close
                    if (touchStartX > 100 && horizontalDiff < -swipeThreshold) {
                        if (window.innerWidth < 768 && sidebar && sidebar.classList.contains('active')) {
                            closeSidebar();
                        }
                    }
                }
            }
            
            // Keyboard shortcut (Escape to close)
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && window.innerWidth < 768) {
                    if (sidebar && sidebar.classList.contains('active')) {
                        closeSidebar();
                    }
                }
            });
            
            // Initial setup
            updateToggleButtonVisibility();
            
            // ========== ACTIVE LINK HIGHLIGHT ==========
            const currentPath = window.location.pathname;
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.startsWith(href) && href !== '#') {
                    link.closest('.nav-item')?.classList.add('active');
                    
                    // Expand parent collapse if exists
                    const collapse = link.closest('.collapse');
                    if (collapse) {
                        collapse.classList.add('show');
                        const toggler = document.querySelector(`[data-bs-target="#${collapse.id}"]`);
                        if (toggler) {
                            toggler.classList.remove('collapsed');
                        }
                    }
                }
            });
        });
    </script>
    
    @stack('scripts')

</body>
</html>