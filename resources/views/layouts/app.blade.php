<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#115B39">
    <meta name="description" content="Sistem Informasi Manajemen Bank Sampah Buha Recycle Manado">
    
    {{-- Preconnect untuk performa --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    
    <title>@yield('title', 'Recycle Manado') | Bank Sampah Buha</title>
    
    {{-- Critical CSS Inline --}}
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-width-tablet: 240px;
            --header-height: 60px;
            --primary: #115B39;
            --primary-dark: #073520;
            --warning: #ffc107;
            --transition-speed: 0.3s;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        #wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }
        
        #main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left var(--transition-speed) ease;
            width: 100%;
        }
        
        .page-content {
            flex: 1;
            padding: 1rem;
            transition: padding var(--transition-speed) ease;
        }
        
        /* ========== DESKTOP (> 1024px) ========== */
        @media (min-width: 1025px) {
            #main-content {
                margin-left: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
            }
            .page-content { 
                padding: 2rem;
                padding-top: 1.5rem; /* Jarak dari top (navbar sudah di atas) */
            }
        }
        
        /* ========== TABLET (768px - 1024px) ========== */
        @media (min-width: 768px) and (max-width: 1024px) {
            #main-content {
                margin-left: var(--sidebar-width-tablet);
                width: calc(100% - var(--sidebar-width-tablet));
            }
            .page-content { 
                padding: 1.5rem;
                padding-top: 1.25rem;
            }
        }
        
        /* ========== MOBILE (< 768px) ========== */
        @media (max-width: 767.98px) {
            #main-content {
                margin-left: 0 !important;
                width: 100% !important;
                /* ✅ PERBAIKAN: padding-top untuk navbar fixed */
                padding-top: var(--header-height);
            }
            .page-content { 
                padding: 0.875rem;
                /* ✅ Tidak perlu padding-top lagi karena sudah di #main-content */
            }
            body.sidebar-open { overflow: hidden; }
        }
        
        /* ========== SMALL MOBILE (< 480px) ========== */
        @media (max-width: 480px) {
            .page-content { padding: 0.75rem; }
        }
        
        /* ========== ANIMATIONS ========== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in { animation: fadeIn 0.3s ease; }
        
        /* ========== SCROLLBAR ========== */
        @media (min-width: 768px) {
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
            ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #a1a1a1; }
        }
        
        /* ========== TOUCH-FRIENDLY ========== */
        @media (hover: none) and (pointer: coarse) {
            .btn, .nav-link, [role="button"] {
                min-height: 44px;
                min-width: 44px;
            }
        }
        
        /* ========== PRINT ========== */
        @media print {
            .sidebar, #sidebarOverlay, #sidebarToggleMobile, .navbar { display: none !important; }
            #main-content { margin-left: 0 !important; width: 100% !important; padding-top: 0 !important; }
        }
    </style>
    
    {{-- Bootstrap CSS (deferred) --}}
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></noscript>
    
    {{-- Font Awesome (deferred) --}}
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"></noscript>
    
    {{-- Bootstrap Icons (deferred) --}}
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" media="print" onload="this.media='all'">
    
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
            <div class="container-fluid fade-in">
                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        @include('layouts.partials.footer')

    </div>

</div>

    {{-- Scripts (deferred where possible) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    
    {{-- App JS --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    {{-- Responsive Sidebar Handler --}}
    <script>
        (function() {
            'use strict';
            
            let sidebar, sidebarOverlay, sidebarToggleMobile, closeSidebarMobile;
            let touchStartX = 0, touchStartY = 0, isSwiping = false;
            
            function init() {
                sidebar = document.querySelector('.sidebar');
                sidebarOverlay = document.getElementById('sidebarOverlay');
                sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
                closeSidebarMobile = document.getElementById('closeSidebarMobile');
                
                setupEventListeners();
                updateToggleButtonVisibility();
                highlightActiveLink();
            }
            
            function setupEventListeners() {
                // Toggle button
                if (sidebarToggleMobile) {
                    sidebarToggleMobile.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        openSidebar();
                    });
                }
                
                // Close button
                if (closeSidebarMobile) {
                    closeSidebarMobile.addEventListener('click', function(e) {
                        e.preventDefault();
                        closeSidebar();
                    });
                }
                
                // Overlay click
                if (sidebarOverlay) {
                    sidebarOverlay.addEventListener('click', closeSidebar);
                }
                
                // Nav links (close sidebar on mobile)
                document.querySelectorAll('.sidebar .nav-link').forEach(function(link) {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 768 && !this.hasAttribute('data-bs-toggle')) {
                            setTimeout(closeSidebar, 200);
                        }
                    });
                });
                
                // Window resize (throttled)
                let resizeTimer;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(function() {
                        updateToggleButtonVisibility();
                        if (window.innerWidth >= 768) closeSidebar();
                    }, 150);
                });
                
                // Touch gestures
                document.addEventListener('touchstart', handleTouchStart, { passive: true });
                document.addEventListener('touchmove', handleTouchMove, { passive: false });
                document.addEventListener('touchend', handleTouchEnd, { passive: true });
                
                // Keyboard escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && window.innerWidth < 768) {
                        if (sidebar && sidebar.classList.contains('active')) {
                            closeSidebar();
                        }
                    }
                });
            }
            
            function openSidebar() {
                if (!sidebar) return;
                sidebar.classList.add('active');
                if (sidebarOverlay) sidebarOverlay.style.display = 'block';
                document.body.classList.add('sidebar-open');
            }
            
            function closeSidebar() {
                if (!sidebar) return;
                sidebar.classList.remove('active');
                if (sidebarOverlay) sidebarOverlay.style.display = 'none';
                document.body.classList.remove('sidebar-open');
            }
            
            function updateToggleButtonVisibility() {
                if (!sidebarToggleMobile) return;
                sidebarToggleMobile.style.display = window.innerWidth < 768 ? 'flex' : 'none';
            }
            
            function handleTouchStart(e) {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
                isSwiping = true;
            }
            
            function handleTouchMove(e) {
                if (!isSwiping) return;
                const deltaX = Math.abs(e.touches[0].clientX - touchStartX);
                const deltaY = Math.abs(e.touches[0].clientY - touchStartY);
                if (deltaX > deltaY && deltaX > 10) {
                    e.preventDefault();
                }
            }
            
            function handleTouchEnd(e) {
                if (!isSwiping) return;
                isSwiping = false;
                
                const touchEndX = e.changedTouches[0].clientX;
                const touchEndY = e.changedTouches[0].clientY;
                const deltaX = touchEndX - touchStartX;
                const deltaY = Math.abs(touchEndY - touchStartY);
                
                if (Math.abs(deltaX) > deltaY && Math.abs(deltaX) > 50) {
                    if (window.innerWidth < 768) {
                        if (deltaX > 0 && touchStartX < 40) {
                            openSidebar();
                        } else if (deltaX < 0 && touchStartX > 100) {
                            if (sidebar && sidebar.classList.contains('active')) {
                                closeSidebar();
                            }
                        }
                    }
                }
            }
            
            function highlightActiveLink() {
                const currentPath = window.location.pathname;
                document.querySelectorAll('.sidebar .nav-link').forEach(function(link) {
                    const href = link.getAttribute('href');
                    if (href && currentPath.startsWith(href) && href !== '#') {
                        const navItem = link.closest('.nav-item');
                        if (navItem) navItem.classList.add('active');
                        
                        const collapse = link.closest('.collapse');
                        if (collapse) {
                            collapse.classList.add('show');
                            const toggler = document.querySelector('[data-bs-target="#' + collapse.id + '"]');
                            if (toggler) toggler.classList.remove('collapsed');
                        }
                    }
                });
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
    
    @stack('scripts')

</body>
</html>