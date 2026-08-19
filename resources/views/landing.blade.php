{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <meta name="theme-color" content="#1a5632" />
    <meta name="description" content="Bank Sampah Buha membeli/Menerima segala jenis plastik — PET, PP, HDPE, dan LDPE. Berlokasi di Bailang, Kota Manado, Sulawesi Utara." />
    
    {{-- Preload critical assets --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="dns-prefetch" href="//unpkg.com" />
    <link rel="dns-prefetch" href="//maps.google.com" />
    
    <title>Bank Sampah Buha – Recycle Manado</title>

    {{-- Critical CSS Inline --}}
    <style>
        /* Critical CSS - load immediately */
        :root {
            --clr-primary: #1a5632;
            --clr-primary-glow: #2d8a4e;
            --clr-bg: #faf7f2;
            --clr-card: #ffffff;
            --clr-secondary: #f0ece6;
            --clr-accent: #d4edda;
            --clr-border: #c8e0ce;
            --clr-fg: #1a1f1a;
            --clr-muted: #6b7c6f;
            --clr-wa: #25D366;
            --clr-wa-hover: #1ea952;
            --font-display: 'DM Serif Display', Georgia, serif;
            --font-body: 'DM Sans', system-ui, sans-serif;
            --transition: 0.25s cubic-bezier(.4,0,.2,1);
            --radius-xl: 1.5rem;
            --radius-2xl: 2rem;
            --shadow-soft: 0 2px 16px rgba(0,0,0,.08);
            --shadow-elegant: 0 8px 40px rgba(0,0,0,.14);
        }
        
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; -webkit-text-size-adjust: 100%; }
        
        body {
            font-family: var(--font-body);
            background: var(--clr-bg);
            color: var(--clr-fg);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        img { 
            display: block; 
            max-width: 100%; 
            height: auto;
            content-visibility: auto; /* Performance optimization */
        }
        
        a { text-decoration: none; color: inherit; }
        
        /* Container */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }
        
        @media (min-width: 768px) {
            .container { padding: 0 2rem; }
        }
        
        /* Header Styles */
        .site-header {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            transition: background .3s, box-shadow .3s;
            will-change: background;
        }
        
        .site-header.scrolled {
            background: rgba(250, 247, 242, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: var(--shadow-soft);
            border-bottom: 1px solid var(--clr-border);
        }
        
        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }
        
        @media (min-width: 768px) {
            .header-inner { height: 80px; }
        }
        
        /* Logo */
        .logo {
            display: flex;
            align-items: center;
            gap: .75rem;
            z-index: 101;
        }
        
        .logo-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 38px; 
            width: 38px;
            border-radius: 12px;
            background: linear-gradient(135deg, #1a5632, #2d8a4e);
            color: #fff;
            transition: transform var(--transition);
        }
        
        .logo:hover .logo-icon { transform: scale(1.05); }
        
        .logo-name {
            font-family: var(--font-display);
            font-size: 1rem;
            line-height: 1.2;
            color: #fff;
        }
        
        .logo-sub { 
            font-size: .7rem; 
            color: rgba(255,255,255,.75); 
        }
        
        .header-transparent .logo-name,
        .header-transparent .logo-sub { color: #fff; }
        
        .scrolled .logo-name { color: var(--clr-fg); }
        .scrolled .logo-sub { color: var(--clr-muted); }
        
        @media (min-width: 768px) {
            .logo-icon { height: 42px; width: 42px; }
            .logo-name { font-size: 1.1rem; }
            .logo-sub { font-size: .75rem; }
        }
        
        /* Navigation */
        .nav-desktop {
            display: none;
        }
        
        @media (min-width: 768px) {
            .nav-desktop {
                display: flex;
                align-items: center;
                gap: 1.5rem;
                list-style: none;
            }
            
            .nav-desktop a {
                font-size: .9rem;
                font-weight: 500;
                color: #fff;
                opacity: .9;
                transition: opacity var(--transition);
                white-space: nowrap;
            }
            
            .nav-desktop a:hover { opacity: 1; color: hsl(150, 60%, 75%); }
            
            .scrolled .nav-desktop a { 
                color: var(--clr-fg); 
                opacity: .8; 
            }
            
            .scrolled .nav-desktop a:hover { 
                color: var(--clr-primary-glow); 
                opacity: 1; 
            }
        }
        
        @media (min-width: 1024px) {
            .nav-desktop { gap: 2rem; }
        }
        
        /* CTA Header */
        .header-cta {
            display: none;
        }
        
        @media (min-width: 768px) {
            .header-cta {
                display: flex;
                align-items: center;
                gap: .75rem;
            }
        }
        
        /* Mobile Menu */
        .menu-toggle {
            display: flex;
            background: none;
            border: none;
            cursor: pointer;
            padding: .5rem;
            color: #fff;
            z-index: 101;
        }
        
        .scrolled .menu-toggle { color: var(--clr-fg); }
        
        @media (min-width: 768px) {
            .menu-toggle { display: none; }
        }
        
        .mobile-nav {
            display: none;
            flex-direction: column;
            gap: .25rem;
            padding: 1rem 1.25rem 1.25rem;
            background: var(--clr-bg);
            border-top: 1px solid var(--clr-border);
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            z-index: 99;
            box-shadow: var(--shadow-soft);
            max-height: calc(100vh - 70px);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .mobile-nav.open { display: flex; }
        
        .mobile-nav a {
            padding: .75rem 0;
            font-weight: 500;
            color: var(--clr-fg);
            border-bottom: 1px solid #eee;
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .65rem 1.25rem;
            border-radius: 9999px;
            font-family: var(--font-body);
            font-weight: 600;
            font-size: .9rem;
            cursor: pointer;
            border: none;
            white-space: nowrap;
            transition: transform .2s, box-shadow .2s, background .2s;
        }
        
        .btn-xl { padding: .85rem 1.75rem; font-size: .95rem; }
        
        .btn-hero {
            background: #fff;
            color: var(--clr-primary);
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
        }
        
        .btn-hero:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 28px rgba(0,0,0,.2); 
        }
        
        .btn-glass {
            background: rgba(255,255,255,.15);
            color: #fff;
            border: 1.5px solid rgba(255,255,255,.35);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
        
        .btn-glass:hover { 
            background: rgba(255,255,255,.25); 
            transform: translateY(-2px); 
        }
        
        /* WhatsApp Button */
        .btn-wa {
            background: var(--clr-wa);
            color: #fff;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.3);
        }
        
        .btn-wa:hover { 
            background: var(--clr-wa-hover);
            transform: translateY(-2px); 
            box-shadow: 0 8px 28px rgba(37, 211, 102, 0.4);
        }
        
        .btn-wa-outline {
            background: transparent;
            color: var(--clr-wa);
            border: 2px solid var(--clr-wa);
        }
        
        .btn-wa-outline:hover {
            background: var(--clr-wa);
            color: #fff;
            transform: translateY(-2px);
        }
        
        @media (max-width: 359px) {
            .btn { padding: .55rem 1rem; font-size: .8rem; }
            .btn-xl { padding: .7rem 1.5rem; font-size: .85rem; }
        }
    </style>

    {{-- Non-critical CSS loaded asynchronously --}}
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@400;500;600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"></noscript>

    <style>
        /* Non-critical styles loaded after */
        @media (min-width: 1200px) {
            .container { padding: 0; }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse {
            0%, 100% { box-shadow: 0 4px 20px rgba(37, 211, 102, 0.3); }
            50% { box-shadow: 0 4px 30px rgba(37, 211, 102, 0.6); }
        }
        
        .btn-wa-pulse {
            animation: pulse 2s infinite;
        }
        
        /* Hero Section */
        .hero {
            position: relative;
            min-height: 100vh;
            min-height: -webkit-fill-available;
            display: flex;
            align-items: center;
            overflow: hidden;
            background: var(--clr-primary);
        }
        
        .hero-bg {
            position: absolute;
            inset: 0;
            width: 100%; 
            height: 100%;
            object-fit: cover;
            opacity: .8;
        }
        
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, rgba(26,86,50,.85) 0%, rgba(26,86,50,.6) 100%);
        }
        
        .hero-content {
            position: relative;
            z-index: 10;
            padding: 6rem 0 4rem;
            max-width: 780px;
            animation: fadeInUp .8s ease both;
        }
        
        @media (min-width: 768px) {
            .hero-content { padding: 8rem 0 6rem; }
        }
        
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            border: 1px solid rgba(255,255,255,.3);
            background: rgba(255,255,255,.1);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            padding: .45rem 1rem;
            border-radius: 9999px;
            font-size: .8rem;
            color: #fff;
            margin-bottom: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .hero-badge { 
                padding: .5rem 1.1rem; 
                font-size: .85rem; 
                margin-bottom: 2rem; 
            }
        }
        
        .hero h1 {
            font-family: var(--font-display);
            font-size: clamp(2rem, 5vw, 4rem);
            color: #fff;
            line-height: 1.1;
            margin-bottom: 1rem;
        }
        
        @media (min-width: 768px) {
            .hero h1 { margin-bottom: 1.25rem; }
        }
        
        .hero h1 em { 
            font-style: italic; 
            color: hsl(150, 60%, 72%); 
        }
        
        .hero-lead {
            font-size: clamp(.95rem, 1.5vw, 1.1rem);
            color: rgba(255,255,255,.85);
            max-width: 600px;
            margin-bottom: 2rem;
            line-height: 1.7;
        }
        
        .hero-btns { 
            display: flex; 
            flex-wrap: wrap; 
            gap: .75rem; 
        }
        
        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 3rem;
            max-width: 440px;
        }
        
        @media (min-width: 768px) {
            .hero-stats { 
                margin-top: 4rem; 
                gap: 1.5rem; 
            }
        }
        
        .hero-stat {
            border-left: 2px solid rgba(255,255,255,.3);
            padding-left: .75rem;
        }
        
        @media (min-width: 768px) {
            .hero-stat { padding-left: 1rem; }
        }
        
        .hero-stat-val {
            font-family: var(--font-display);
            font-size: 1.5rem;
            color: #fff;
            font-weight: 600;
        }
        
        @media (min-width: 768px) {
            .hero-stat-val { font-size: 2rem; }
        }
        
        .hero-stat-lbl { 
            font-size: .75rem; 
            color: rgba(255,255,255,.7); 
        }
        
        /* Section Common */
        .section-label {
            display: inline-block;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--clr-primary-glow);
            margin-bottom: .75rem;
        }
        
        @media (min-width: 768px) {
            .section-label { 
                font-size: .75rem; 
                margin-bottom: 1rem; 
            }
        }
        
        .section-heading {
            font-family: var(--font-display);
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            color: var(--clr-fg);
            line-height: 1.15;
        }
        
        @media (min-width: 768px) {
            .section-heading { font-size: clamp(2rem, 4vw, 3.2rem); }
        }
        
        .section-heading em { 
            font-style: italic; 
            color: var(--clr-primary); 
        }
        
        /* Sections */
        section { padding: 4rem 0; }
        
        @media (min-width: 768px) {
            section { padding: 6rem 0; }
        }
        
        @media (min-width: 1024px) {
            section { padding: 7rem 0; }
        }
        
        /* About Section */
        .tentang { background: linear-gradient(180deg, #f0ece6 0%, #faf7f2 100%); }
        
        .tentang-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
        
        @media (min-width: 768px) {
            .tentang-grid {
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                align-items: center;
            }
        }
        
        @media (min-width: 1024px) {
            .tentang-grid { gap: 4rem; }
        }
        
        .tentang-img-wrap {
            position: relative;
            order: 2;
        }
        
        @media (min-width: 768px) {
            .tentang-img-wrap { order: 1; }
        }
        
        .tentang-img {
            position: relative;
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-elegant);
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
        }
        
        @media (min-width: 768px) {
            .tentang-img { max-height: 480px; }
        }
        
        .tentang-badge {
            position: absolute;
            bottom: -1rem;
            right: -.5rem;
            background: #fff;
            border-radius: 12px;
            padding: .85rem 1rem;
            box-shadow: var(--shadow-elegant);
            border: 1px solid var(--clr-border);
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        
        @media (min-width: 768px) {
            .tentang-badge { 
                bottom: -1.5rem; 
                right: -1.5rem; 
                padding: 1.1rem 1.25rem; 
            }
        }
        
        .tentang-badge-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px; 
            width: 40px;
            border-radius: 10px;
            background: var(--clr-accent);
            color: var(--clr-primary);
        }
        
        @media (min-width: 768px) {
            .tentang-badge-icon { height: 48px; width: 48px; }
        }
        
        .tentang-badge-title { 
            font-family: var(--font-display); 
            font-size: 1rem; 
        }
        
        .tentang-badge-sub { 
            font-size: .75rem; 
            color: var(--clr-muted); 
        }
        
        .tentang-body { order: 1; }
        
        @media (min-width: 768px) {
            .tentang-body { order: 2; }
        }
        
        .tentang-body p { 
            color: var(--clr-muted); 
            font-size: .95rem; 
            line-height: 1.7; 
            margin-bottom: 1rem; 
        }
        
        @media (min-width: 768px) {
            .tentang-body p { 
                font-size: 1.05rem; 
                margin-bottom: 1.25rem; 
            }
        }
        
        .tentang-cards { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: .75rem; 
            margin-top: 1.25rem; 
        }
        
        @media (min-width: 768px) {
            .tentang-cards { gap: 1rem; }
        }
        
        .tentang-card {
            border: 1px solid var(--clr-border);
            background: #fff;
            border-radius: 12px;
            padding: 1rem;
        }
        
        @media (min-width: 768px) {
            .tentang-card { 
                border-radius: 1rem; 
                padding: 1.25rem; 
            }
        }
        
        /* Material Cards */
        .material-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }
        
        @media (min-width: 480px) {
            .material-grid { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (min-width: 768px) {
            .material-grid { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (min-width: 1024px) {
            .material-grid { grid-template-columns: repeat(4, 1fr); }
        }
        
        /* Services Grid */
        .layanan-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        @media (min-width: 480px) {
            .layanan-grid { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (min-width: 1024px) {
            .layanan-grid { grid-template-columns: repeat(4, 1fr); }
        }
        
        /* Contact Cards */
        .kontak-inner {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            padding: 2rem;
        }
        
        @media (min-width: 768px) {
            .kontak-inner { 
                grid-template-columns: 1fr 1fr; 
                gap: 2.5rem; 
                padding: 3rem; 
            }
        }
        
        @media (min-width: 1024px) {
            .kontak-inner { gap: 3rem; padding: 4rem; }
        }
        
        /* Footer */
        .site-footer {
            background: var(--clr-primary);
            color: rgba(255,255,255,.75);
            padding: 2.5rem 0;
        }
        
        @media (min-width: 768px) {
            .site-footer { padding: 3rem 0; }
        }
        
        .footer-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.25rem;
            text-align: center;
        }
        
        @media (min-width: 768px) {
            .footer-inner {
                flex-direction: row;
                justify-content: space-between;
                text-align: left;
            }
        }
        
        /* Touch-friendly adjustments */
        @media (hover: none) and (pointer: coarse) {
            .btn:active { transform: scale(.97); }
            a, button { min-height: 44px; min-width: 44px; }
        }
        
        /* Print styles */
        @media print {
            .site-header, .menu-toggle, .mobile-nav { display: none; }
            .hero { min-height: auto; }
            section { padding: 1rem 0; }
        }
    </style>
</head>

<body>

@php
    $phone1 = '081261834545';
    $phone2 = '085823349268';
    $address = 'Jl. Bailang Raya, Bailang, Kec. Bunaken, Kota Manado, Sulawesi Utara';
    $mapsQuery = '1.5232990627986893, 124.86356772200199';
    
    // WhatsApp URL (format internasional tanpa + atau 0 di depan)
    $waNumber = '6281261834545'; // 081261834545 -> 6281261834545
    $waMessage = 'Halo%20Bank%20Sampah%20Buha%2C%20saya%20ingin%20bertanya%20tentang%20setor%20plastik.';
    $waUrl = 'https://wa.me/' . $waNumber . '?text=' . $waMessage;

    $navLinks = [
        ['href' => '#tentang', 'label' => 'Tentang'],
        ['href' => '#material', 'label' => 'Material'],
        ['href' => '#layanan', 'label' => 'Layanan'],
        ['href' => '#jam',     'label' => 'Jam Buka'],
        ['href' => '#kontak',  'label' => 'Kontak'],
    ];

    $materials = [
        [
            'code'  => 'PET',
            'name'  => 'Polyethylene Terephthalate',
            'desc'  => 'Botol minuman bening, kemasan air mineral.',
            'image' => asset('images/collection-bags.jpg'),
        ],
        [
            'code'  => 'PP',
            'name'  => 'Polypropylene',
            'desc'  => 'Gelas plastik, tutup botol, kemasan makanan.',
            'image' => asset('images/pellets-pp.jpg'),
        ],
        [
            'code'  => 'HDPE',
            'name'  => 'High-Density Polyethylene',
            'desc'  => 'Botol shampo, jerigen, kemasan deterjen.',
            'image' => asset('images/pellets-hdpe.jpg'),
        ],
        [
            'code'  => 'LDPE',
            'name'  => 'Low-Density Polyethylene',
            'desc'  => 'Plastik kresek, kantong belanja, plastik bening tipis.',
            'image' => asset('images/baled-ldpe.jpg'),
        ],
    ];

    $services = [
        [
            'icon'  => 'truck',
            'title' => 'Pengumpulan',
            'desc'  => 'Kami menerima setoran plastik bekas dari rumah tangga, usaha, dan pemulung.',
        ],
        [
            'icon'  => 'recycle',
            'title' => 'Pemilahan & Pengepresan',
            'desc'  => 'Plastik dipilah berdasarkan jenis, dibersihkan, lalu dipress untuk distribusi.',
        ],
        [
            'icon'  => 'factory',
            'title' => 'Pengolahan Pelet',
            'desc'  => 'Sebagian material diolah menjadi pelet siap pakai untuk industri daur ulang.',
        ],
        [
            'icon'  => 'sprout',
            'title' => 'Dampak Lingkungan',
            'desc'  => 'Setiap kilogram yang Anda setor mengurangi sampah laut dan polusi di Manado.',
        ],
    ];

    $hours = [
        ['day' => 'Senin',  'time' => '08.00 – 17.00', 'closed' => false],
        ['day' => 'Selasa', 'time' => '08.00 – 17.00', 'closed' => false],
        ['day' => 'Rabu',   'time' => '08.00 – 17.00', 'closed' => false],
        ['day' => 'Kamis',  'time' => '08.00 – 17.00', 'closed' => false],
        ['day' => 'Jumat',  'time' => '08.00 – 17.00', 'closed' => false],
        ['day' => 'Sabtu',  'time' => '08.00 – 17.00', 'closed' => false],
        ['day' => 'Minggu', 'time' => 'Tutup',         'closed' => true ],
    ];
@endphp

{{-- Header --}}
<header class="site-header header-transparent" id="site-header">
    <div class="container">
        <div class="header-inner">
            <a href="#top" class="logo" aria-label="Bank Sampah Buha - Kembali ke atas">
                <span class="logo-icon" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22L6.66 19.7C7.14 19.87 7.64 20 8 20C19 20 22 3 22 3C21 5 14 5.25 9 6.25C4 7.25 2 11.5 2 13.5C2 15.5 3.75 17.25 3.75 17.25C7 8 17 8 17 8Z"/>
                    </svg>
                </span>
                <div>
                    <div class="logo-name">Bank Sampah Buha</div>
                    <div class="logo-sub">Recycle Manado</div>
                </div>
            </a>

            <nav class="nav-desktop" aria-label="Navigasi utama">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}">{{ $link['label'] }}</a>
                @endforeach
            </nav>

            <div class="header-cta">
                <a href="{{ route('login') }}" class="btn btn-glass" style="padding: 0.45rem 1.1rem; font-size: .8rem;" aria-label="Login Staff">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M13.8 12H3"/>
                    </svg>
                    Login Staff
                </a>
                <a href="{{ $waUrl }}" target="_blank" rel="noreferrer" class="btn btn-wa" aria-label="Chat WhatsApp">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Chat WhatsApp
                </a>
            </div>

            <button class="menu-toggle" id="menu-toggle" aria-label="Buka menu navigasi" aria-expanded="false">
                <svg id="menu-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="mobile-nav" id="mobile-nav" role="navigation" aria-label="Menu mobile">
        @foreach ($navLinks as $link)
            <a href="{{ $link['href'] }}">{{ $link['label'] }}</a>
        @endforeach
       <a href="{{ route('login') }}" class="btn btn-glass" style="margin-top: .5rem; justify-content: center; display: flex;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M13.8 12H3"/>
            </svg>
            Login Staff
        </a>
        <a href="{{ $waUrl }}" target="_blank" rel="noreferrer" class="btn btn-wa" style="margin-top: .5rem; justify-content: center; display: flex;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            Chat WhatsApp
        </a>
    </div>
</header>

<main>
    {{-- Hero Section --}}
    <section id="top" class="hero" aria-label="Bagian utama">
        <img
            src="{{ asset('images/hero-nature.jpg') }}"
            alt="Hutan tropis hijau melambangkan keberlanjutan"
            class="hero-bg"
            fetchpriority="high"
            decoding="async"
        />
        <div class="hero-overlay" aria-hidden="true"></div>

        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M7 21h10M12 21v-4m-9-4h18M5 13l7-9 7 9"/>
                    </svg>
                    <span>Daur Ulang untuk Manado yang Lebih Hijau</span>
                </div>

                <h1>Sampah plastik Anda, <em>sumber daya</em> kami.</h1>

                <p class="hero-lead">
                    Bank Sampah Buha membeli/Menerima segala jenis plastik — PET, PP, HDPE, dan LDPE. Setor di Bailang, Manado dan jadilah bagian dari perubahan.
                </p>

                <div class="hero-btns">
                    <a href="{{ $waUrl }}" target="_blank" rel="noreferrer" class="btn btn-wa btn-xl btn-wa-pulse">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Chat WhatsApp Sekarang
                    </a>
                    <a href="#material" class="btn btn-glass btn-xl">Lihat Material</a>
                </div>

                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-val">4</div>
                        <div class="hero-stat-lbl">Jenis Plastik</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-val">6</div>
                        <div class="hero-stat-lbl">Hari/Minggu</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-val">100%</div>
                        <div class="hero-stat-lbl">Daur Ulang</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- About Section --}}
    <section id="tentang" class="tentang" aria-labelledby="tentang-heading">
        <div class="container">
            <div class="tentang-grid">
                <div class="tentang-img-wrap">
                    <img
                        src="{{ asset('images/facility-yard.jpg') }}"
                        alt="Halaman fasilitas Bank Sampah Buha di Bailang Manado"
                        loading="lazy"
                        decoding="async"
                        class="tentang-img"
                    />
                    <div class="tentang-badge" aria-label="Melayani Manado sejak berdiri">
                        <div class="tentang-badge-icon" aria-hidden="true">
                        </div>
                        <div>
                           
                        </div>
                    </div>
                </div>

                <div class="tentang-body">
                    <span class="section-label">Tentang Kami</span>
                    <h2 class="section-heading" id="tentang-heading" style="margin-bottom:1.25rem;">
                        Mitra terpercaya untuk <em>daur ulang plastik</em> di Sulawesi Utara.
                    </h2>
                    <p>
                        Bank Sampah Buha (Recycle Manado) berlokasi di Bailang, Kota Manado. Kami bergerak di pengumpulan, pemilahan, dan pengolahan plastik bekas menjadi material bernilai ekonomis.
                    </p>
                    <p>
                        Komitmen kami sederhana — <strong>membeli/Menerima segala jenis plastik</strong> dari masyarakat, memberi harga yang adil, dan memastikan setiap kilogram diolah dengan benar.
                    </p>

                    <div class="tentang-cards">
                        <div class="tentang-card">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--clr-primary-glow)" stroke-width="2" aria-hidden="true" style="margin-bottom: .75rem;">
                                <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22L6.66 19.7C7.14 19.87 7.64 20 8 20C19 20 22 3 22 3C21 5 14 5.25 9 6.25C4 7.25 2 11.5 2 13.5C2 15.5 3.75 17.25 3.75 17.25C7 8 17 8 17 8Z"/>
                            </svg>
                            <div style="font-family: var(--font-display); font-size: 1rem; margin-bottom: .25rem;">Ramah Lingkungan</div>
                            <div style="font-size: .8rem; color: var(--clr-muted);">Mengurangi sampah TPA & laut.</div>
                        </div>
                        <div class="tentang-card">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--clr-primary-glow)" stroke-width="2" aria-hidden="true" style="margin-bottom: .75rem;">
                                <rect x="1" y="3" width="15" height="13"/>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                                <circle cx="5.5" cy="18.5" r="2.5"/>
                                <circle cx="18.5" cy="18.5" r="2.5"/>
                            </svg>
                            <div style="font-family: var(--font-display); font-size: 1rem; margin-bottom: .25rem;">Setor Mudah</div>
                            <div style="font-size: .8rem; color: var(--clr-muted);">Buka 6 hari, lokasi strategis.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Material Section --}}
    <section id="material" class="material" aria-labelledby="material-heading">
        <div class="container">
            <div style="max-width: 620px; margin-bottom: 2.5rem;">
                <span class="section-label">Material yang Kami Beli</span>
                <h2 class="section-heading" id="material-heading">Segala jenis plastik, satu tempat.</h2>
            </div>

            <div class="material-grid">
                @foreach ($materials as $i => $m)
                    <article style="
                        border-radius: 1.5rem;
                        overflow: hidden;
                        background: #fff;
                        border: 1px solid var(--clr-border);
                        box-shadow: var(--shadow-soft);
                        transition: transform .3s, box-shadow .3s;
                    ">
                        <div style="position: relative; height: 200px; overflow: hidden;">
                            <img
                                src="{{ $m['image'] }}"
                                alt="Plastik {{ $m['code'] }} - {{ $m['name'] }}"
                                loading="lazy"
                                decoding="async"
                                style="width: 100%; height: 100%; object-fit: cover;"
                            />
                            <span style="
                                position: absolute;
                                top: .75rem; left: .75rem;
                                background: rgba(255,255,255,.95);
                                color: var(--clr-primary);
                                font-weight: 700;
                                font-size: .7rem;
                                letter-spacing: .05em;
                                padding: .3rem .7rem;
                                border-radius: 9999px;
                            ">#{{ $i + 1 }}</span>
                        </div>
                        <div style="padding: 1.25rem;">
                            <div style="font-family: var(--font-display); font-size: 1.8rem; color: var(--clr-primary); margin-bottom: .2rem;">{{ $m['code'] }}</div>
                            <div style="font-size: .8rem; font-weight: 600; color: var(--clr-fg); margin-bottom: .5rem;">{{ $m['name'] }}</div>
                            <p style="font-size: .78rem; color: var(--clr-muted); line-height: 1.6;">{{ $m['desc'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section id="layanan" class="layanan" style="background: var(--clr-secondary);" aria-labelledby="layanan-heading">
        <div class="container">
            <div style="margin-bottom: 2.5rem;">
                <span class="section-label">Cara Kerja Kami</span>
                <h2 class="section-heading" id="layanan-heading">Dari setoran Anda <em>menjadi material baru.</em></h2>
                <p style="font-size: .9rem; color: var(--clr-muted); line-height: 1.7; margin-top: .75rem; max-width: 600px;">
                    Kami menjalankan proses lengkap — mulai dari pengumpulan, pemilahan, hingga pengolahan menjadi pelet plastik berkualitas untuk industri.
                </p>
            </div>

            <div class="layanan-grid">
                @foreach ($services as $i => $s)
                    <div style="
                        position: relative;
                        border-radius: 1.25rem;
                        background: #fff;
                        padding: 1.5rem;
                        border: 1px solid var(--clr-border);
                    ">
                        <div style="
                            position: absolute;
                            top: 1.25rem; right: 1.25rem;
                            font-family: var(--font-display);
                            font-size: 2.5rem;
                            color: var(--clr-accent);
                            line-height: 1;
                        " aria-hidden="true">0{{ $i + 1 }}</div>
                        <div style="
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            height: 48px; width: 48px;
                            border-radius: 12px;
                            background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-glow));
                            color: #fff;
                            margin-bottom: 1.25rem;
                        ">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                @if($s['icon'] === 'truck')
                                    <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                                @elseif($s['icon'] === 'recycle')
                                    <path d="M7 19H4.815a1.83 1.83 0 01-1.57-.881 1.785 1.785 0 01-.004-1.784L7.196 9.5"/><path d="M11 19h8.203a1.83 1.83 0 001.556-.89 1.784 1.784 0 000-1.775l-1.226-2.12"/><path d="M14 16l-3-3 3-3"/><path d="M8.227 9L5.06 3.558A1.833 1.833 0 016.63 2h2.764a1.83 1.83 0 011.57.882L14 9"/>
                                @elseif($s['icon'] === 'factory')
                                    <path d="M2 20a2 2 0 002 2h16a2 2 0 002-2V8l-7 5V8l-7 5V4l-3 2v14z"/>
                                @else
                                    <path d="M7 11v8a1 1 0 001 1h8a1 1 0 001-1v-8M12 2v7"/>
                                @endif
                            </svg>
                        </div>
                        <h3 style="font-family: var(--font-display); font-size: 1.1rem; margin-bottom: .5rem;">{{ $s['title'] }}</h3>
                        <p style="font-size: .8rem; color: var(--clr-muted); line-height: 1.6;">{{ $s['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Jam Buka Section --}}
    <section id="jam" class="jam" aria-labelledby="jam-heading">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr; gap: 2.5rem;">
                <div>
                    <span class="section-label">Jam Operasional</span>
                    <h2 class="section-heading" id="jam-heading" style="margin-bottom:1.25rem;">
                        Datang langsung ke <em>lokasi kami.</em>
                    </h2>
                    <p style="color: var(--clr-muted); font-size: .95rem; line-height: 1.7;">
                        Kami buka enam hari seminggu untuk melayani setoran sampah plastik Anda. Hari Minggu kami tutup.
                    </p>
                    <div style="
                        display: flex;
                        align-items: center;
                        gap: 1rem;
                        padding: 1.25rem;
                        border-radius: 1rem;
                        background: var(--clr-accent);
                        margin-top: 1.5rem;
                    ">
                        <div style="
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            height: 48px; width: 48px;
                            border-radius: .75rem;
                            background: var(--clr-primary);
                            color: #fff;
                            flex-shrink: 0;
                        ">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight: 600;">Setiap hari kerja</div>
                            <div style="font-size: .85rem; color: var(--clr-muted);">Pukul 08.00 — 17.00 WITA</div>
                        </div>
                    </div>
                </div>

                <div style="
                    border-radius: 1.5rem;
                    overflow: hidden;
                    background: #fff;
                    border: 1px solid var(--clr-border);
                    box-shadow: var(--shadow-elegant);
                ">
                    <div style="
                        padding: 1.25rem 1.5rem;
                        background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-glow));
                        color: #fff;
                    ">
                        <h3 style="font-family: var(--font-display); font-size: 1.15rem;">Jadwal Mingguan</h3>
                        <span style="font-size: .8rem; opacity: .8;">Bank Sampah Buha</span>
                    </div>
                    @foreach ($hours as $h)
                        <div style="
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            padding: 1rem 1.5rem;
                            border-top: 1px solid var(--clr-border);
                        ">
                            <span style="font-weight: 500;">{{ $h['day'] }}</span>
                            <span style="
                                font-size: .9rem;
                                font-weight: 700;
                                color: {{ $h['closed'] ? '#e74c3c' : 'var(--clr-primary-glow)' }};
                            ">
                                {{ $h['time'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section id="kontak" class="kontak" style="background: linear-gradient(180deg, #f0ece6 0%, #faf7f2 100%);" aria-labelledby="kontak-heading">
        <div class="container">
            <div style="
                border-radius: 2rem;
                overflow: hidden;
                background: var(--clr-primary);
                color: #fff;
                box-shadow: var(--shadow-elegant);
                position: relative;
            ">
                <div class="kontak-inner">
                    <div>
                        <span class="section-label" style="color: hsl(150, 60%, 72%);">Hubungi Kami</span>
                        <h2 id="kontak-heading" style="font-family: var(--font-display); font-size: clamp(1.8rem, 4vw, 2.5rem); color: #fff; line-height: 1.1; margin-bottom: 1rem;">Siap setor plastik Anda hari ini?</h2>
                        <p style="color: rgba(255,255,255,.85); font-size: .95rem; line-height: 1.7; margin-bottom: 1.5rem; max-width: 400px;">
                            Hubungi kami via telepon atau datang langsung ke lokasi. Tim kami siap membantu.
                        </p>

                        <div style="display: flex; flex-direction: column; gap: .75rem;">
                            {{-- WhatsApp Link --}}
                            <a href="{{ $waUrl }}" target="_blank" rel="noreferrer" style="
                                display: flex;
                                align-items: center;
                                gap: .75rem;
                                padding: .85rem;
                                border-radius: 12px;
                                background: rgba(37, 211, 102, 0.2);
                                border: 1.5px solid rgba(37, 211, 102, 0.4);
                                backdrop-filter: blur(6px);
                                -webkit-backdrop-filter: blur(6px);
                                color: #fff;
                                transition: background .2s;
                            ">
                                <div style="
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    height: 42px; width: 42px;
                                    border-radius: 10px;
                                    background: #25D366;
                                    flex-shrink: 0;
                                ">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#fff" aria-hidden="true">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size: .65rem; text-transform: uppercase; letter-spacing: .08em; opacity: .7;">Chat WhatsApp Utama</div>
                                    <div style="font-family: var(--font-display); font-size: 1.15rem;">0812-6183-4545</div>
                                </div>
                            </a>

                            {{-- Telepon Alternatif --}}
                            <a href="tel:{{ $phone2 }}" style="
                                display: flex;
                                align-items: center;
                                gap: .75rem;
                                padding: .85rem;
                                border-radius: 12px;
                                background: rgba(255,255,255,.1);
                                border: 1.5px solid rgba(255,255,255,.15);
                                backdrop-filter: blur(6px);
                                -webkit-backdrop-filter: blur(6px);
                                color: #fff;
                            ">
                                <div style="
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    height: 42px; width: 42px;
                                    border-radius: 10px;
                                    background: var(--clr-primary-glow);
                                    flex-shrink: 0;
                                ">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size: .65rem; text-transform: uppercase; letter-spacing: .08em; opacity: .7;">No. Telepon Alternatif</div>
                                    <div style="font-family: var(--font-display); font-size: 1.15rem;">0858-2334-9268</div>
                                </div>
                            </a>

                            {{-- Alamat --}}
                            <div style="
                                display: flex;
                                align-items: center;
                                gap: .75rem;
                                padding: .85rem;
                                border-radius: 12px;
                                background: rgba(255,255,255,.1);
                                border: 1.5px solid rgba(255,255,255,.15);
                                backdrop-filter: blur(6px);
                                -webkit-backdrop-filter: blur(6px);
                            ">
                                <div style="
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    height: 42px; width: 42px;
                                    border-radius: 10px;
                                    background: var(--clr-primary-glow);
                                    flex-shrink: 0;
                                ">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size: .65rem; text-transform: uppercase; letter-spacing: .08em; opacity: .7;">Alamat</div>
                                    <div style="font-size: .85rem; line-height: 1.5;">{{ $address }}</div>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; flex-wrap: wrap; gap: .75rem; margin-top: 1.5rem;">
                            {{-- Tombol WhatsApp Utama --}}
                            <a href="{{ $waUrl }}" target="_blank" rel="noreferrer" class="btn btn-wa btn-xl btn-wa-pulse">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Chat WhatsApp
                            </a>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $mapsQuery }}" target="_blank" rel="noreferrer" class="btn btn-glass btn-xl">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                Buka di Maps
                            </a>
                        </div>
                    </div>

                    <div style="border-radius: 12px; overflow: hidden; border: 1.5px solid rgba(255,255,255,.15); min-height: 300px;">
                        <iframe
                            title="Lokasi Bank Sampah Buha di Bailang, Manado"
                            src="https://www.google.com/maps?q={{ $mapsQuery }}&output=embed"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen
                            style="width: 100%; height: 100%; min-height: 300px; border: none; display: block;"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

{{-- Footer --}}
<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-inner">
            <div style="display: flex; align-items: center; gap: .75rem;">
                <div style="
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 36px; width: 36px;
                    border-radius: 10px;
                    background: rgba(255,255,255,.12);
                ">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="hsl(150, 60%, 72%)" stroke-width="2" aria-hidden="true">
                        <path d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22L6.66 19.7C7.14 19.87 7.64 20 8 20C19 20 22 3 22 3C21 5 14 5.25 9 6.25C4 7.25 2 11.5 2 13.5C2 15.5 3.75 17.25 3.75 17.25C7 8 17 8 17 8Z"/>
                    </svg>
                </div>
                <div>
                    <div style="font-family: var(--font-display); font-size: 1rem; color: #fff;">Bank Sampah Buha</div>
                    <div style="font-size: .7rem;">Recycle Manado · Bailang</div>
                </div>
            </div>
            <p style="font-size: .8rem; opacity: .8;">
                © {{ date('Y') }} Bank Sampah Buha. Membangun Manado yang lebih hijau.
            </p>
        </div>
    </div>
</footer>

{{-- Minimal JavaScript for header & mobile menu --}}
<script>
    (function() {
        'use strict';
        
        // Header scroll effect
        const header = document.getElementById('site-header');
        let scrollTicking = false;
        
        function updateHeader() {
            if (window.scrollY > 30) {
                header.classList.add('scrolled');
                header.classList.remove('header-transparent');
            } else {
                header.classList.remove('scrolled');
                header.classList.add('header-transparent');
            }
            scrollTicking = false;
        }
        
        window.addEventListener('scroll', function() {
            if (!scrollTicking) {
                requestAnimationFrame(updateHeader);
                scrollTicking = true;
            }
        }, { passive: true });
        
        // Mobile menu toggle
        const toggle = document.getElementById('menu-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        const menuIcon = document.getElementById('menu-icon');
        
        toggle.addEventListener('click', function() {
            const isOpen = mobileNav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen);
            
            if (isOpen) {
                menuIcon.innerHTML = '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>';
                document.body.style.overflow = 'hidden';
            } else {
                menuIcon.innerHTML = '<line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>';
                document.body.style.overflow = '';
            }
        });
        
        // Close mobile menu on link click
        mobileNav.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                mobileNav.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
                menuIcon.innerHTML = '<line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>';
                document.body.style.overflow = '';
            });
        });
        
        // Lazy load iframes
        if ('IntersectionObserver' in window) {
            const iframeObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const iframe = entry.target;
                        if (iframe.dataset.src) {
                            iframe.src = iframe.dataset.src;
                            iframe.removeAttribute('data-src');
                        }
                        iframeObserver.unobserve(iframe);
                    }
                });
            }, { rootMargin: '200px' });
            
            document.querySelectorAll('iframe[loading="lazy"]').forEach(function(iframe) {
                iframeObserver.observe(iframe);
            });
        }
    })();
</script>

</body>
</html>