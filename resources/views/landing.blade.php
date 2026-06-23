{{-- resources/views/landing.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bank Sampah Buha – Recycle Manado</title>
    <meta name="description" content="Bank Sampah Buha membeli/Menerima segala jenis plastik — PET, PP, HDPE, dan LDPE. Berlokasi di Bailang, Kota Manado, Sulawesi Utara." />

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />

    {{-- Lucide Icons via CDN --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>

    <style>
        /* ============================================================
           CSS VARIABLES (mirrors Tailwind theme)
        ============================================================ */
        :root {
            --clr-primary:        hsl(150 55% 28%);
            --clr-primary-glow:   hsl(150 60% 42%);
            --clr-primary-fg:     hsl(0 0% 100%);
            --clr-bg:             hsl(40 30% 97%);
            --clr-card:           hsl(0 0% 100%);
            --clr-secondary:      hsl(40 20% 93%);
            --clr-accent:         hsl(150 40% 90%);
            --clr-border:         hsl(150 20% 85%);
            --clr-fg:             hsl(150 15% 15%);
            --clr-muted:          hsl(150 8% 48%);
            --clr-destructive:    hsl(0 70% 50%);

            --grad-hero:   linear-gradient(160deg, hsl(150 50% 10% / .75) 0%, hsl(150 40% 18% / .55) 100%);
            --grad-leaf:   linear-gradient(135deg, hsl(150 55% 28%) 0%, hsl(150 60% 40%) 100%);
            --grad-earth:  linear-gradient(180deg, hsl(40 25% 94%) 0%, hsl(40 20% 97%) 100%);
            --grad-card:   linear-gradient(145deg, hsl(0 0% 100%) 0%, hsl(150 15% 97%) 100%);

            --shadow-soft:    0 2px 16px hsl(150 30% 20% / .08);
            --shadow-elegant: 0 8px 40px hsl(150 30% 20% / .14);

            --radius-xl: 1.5rem;
            --radius-2xl: 2rem;
            --radius-3xl: 1.5rem;

            --font-display: 'DM Serif Display', Georgia, serif;
            --font-body:    'DM Sans', system-ui, sans-serif;

            --transition: 0.25s cubic-bezier(.4,0,.2,1);
        }

        /* ============================================================
           RESET / BASE
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font-body);
            background: var(--clr-bg);
            color: var(--clr-fg);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        img { display: block; max-width: 100%; }
        a { text-decoration: none; color: inherit; }

        /* ============================================================
           LAYOUT HELPERS
        ============================================================ */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* ============================================================
           BUTTONS
        ============================================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .75rem 1.5rem;
            border-radius: 9999px;
            font-family: var(--font-body);
            font-weight: 600;
            font-size: .95rem;
            cursor: pointer;
            border: none;
            transition: transform var(--transition), box-shadow var(--transition), background var(--transition);
        }
        .btn-xl { padding: 1rem 2rem; font-size: 1rem; }
        .btn-hero {
            background: var(--clr-primary-fg);
            color: var(--clr-primary);
            box-shadow: 0 4px 20px hsl(0 0% 0% / .15);
        }
        .btn-hero:hover { transform: translateY(-2px); box-shadow: 0 8px 28px hsl(0 0% 0% / .2); }
        .btn-glass {
            background: hsl(0 0% 100% / .15);
            color: var(--clr-primary-fg);
            border: 1.5px solid hsl(0 0% 100% / .35);
            backdrop-filter: blur(6px);
        }
        .btn-glass:hover { background: hsl(0 0% 100% / .25); transform: translateY(-2px); }
        .btn-dark {
            background: var(--clr-primary);
            color: var(--clr-primary-fg);
        }
        .btn-dark:hover { background: var(--clr-primary-glow); transform: translateY(-2px); }

        /* ============================================================
           HEADER
        ============================================================ */
        .site-header {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 50;
            transition: background var(--transition), box-shadow var(--transition);
        }
        .site-header.scrolled {
            background: hsl(40 30% 97% / .88);
            backdrop-filter: blur(12px);
            box-shadow: var(--shadow-soft);
            border-bottom: 1px solid var(--clr-border);
        }
        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .logo-icon {
            display: grid;
            place-items: center;
            height: 42px; width: 42px;
            border-radius: .75rem;
            background: var(--grad-leaf);
            color: #fff;
            box-shadow: var(--shadow-soft);
            transition: transform var(--transition);
        }
        .logo:hover .logo-icon { transform: scale(1.07); }
        .logo-name {
            font-family: var(--font-display);
            font-size: 1.1rem;
            line-height: 1.2;
        }
        .logo-sub { font-size: .75rem; color: var(--clr-muted); }
        .header-transparent .logo-sub { color: hsl(0 0% 100% / .75); }
        .header-transparent .logo-name { color: #fff; }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }
        .nav-links a {
            font-size: .9rem;
            font-weight: 500;
            color: var(--clr-fg);
            opacity: .8;
            transition: color var(--transition), opacity var(--transition);
        }
        .nav-links a:hover { color: var(--clr-primary-glow); opacity: 1; }
        .header-transparent .nav-links a { color: #fff; opacity: .9; }
        .header-transparent .nav-links a:hover { color: hsl(150 60% 75%); opacity: 1; }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: .5rem;
            color: var(--clr-fg);
        }
        .header-transparent .menu-toggle { color: #fff; }

        .mobile-nav {
            display: none;
            flex-direction: column;
            gap: .5rem;
            padding: 1rem 1.5rem 1.5rem;
            background: var(--clr-bg);
            border-top: 1px solid var(--clr-border);
        }
        .mobile-nav.open { display: flex; }
        .mobile-nav a {
            padding: .6rem 0;
            font-weight: 500;
            color: var(--clr-fg);
            opacity: .8;
        }

        @media (max-width: 767px) {
            .nav-links, .header-cta { display: none; }
            .menu-toggle { display: block; }
        }
        @media (min-width: 768px) {
            .mobile-nav { display: none !important; }
        }

        /* ============================================================
           HERO
        ============================================================ */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        .hero-bg {
            position: absolute;
            inset: 0;
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: var(--grad-hero);
        }
        .hero-overlay2 {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at bottom, hsl(150 60% 15% / .65), transparent 60%);
        }
        .hero-content {
            position: relative;
            z-index: 10;
            padding-top: 8rem;
            padding-bottom: 6rem;
            max-width: 780px;
            animation: fadeInUp .8s ease both;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            border: 1px solid hsl(0 0% 100% / .3);
            background: hsl(0 0% 100% / .1);
            backdrop-filter: blur(4px);
            padding: .5rem 1.1rem;
            border-radius: 9999px;
            font-size: .85rem;
            color: #fff;
            margin-bottom: 2rem;
        }
        .hero h1 {
            font-family: var(--font-display);
            font-size: clamp(2.6rem, 6vw, 4.5rem);
            color: #fff;
            line-height: 1.06;
            margin-bottom: 1.25rem;
        }
        .hero h1 em { font-style: italic; color: hsl(150 60% 72%); }
        .hero-lead {
            font-size: clamp(1rem, 2vw, 1.2rem);
            color: hsl(0 0% 100% / .85);
            max-width: 640px;
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }
        .hero-btns { display: flex; flex-wrap: wrap; gap: 1rem; }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, auto);
            gap: 1.5rem;
            margin-top: 4rem;
            max-width: 440px;
        }
        .hero-stat {
            border-left: 2px solid hsl(0 0% 100% / .3);
            padding-left: 1rem;
        }
        .hero-stat-val {
            font-family: var(--font-display);
            font-size: 2rem;
            color: #fff;
            font-weight: 600;
        }
        .hero-stat-lbl { font-size: .8rem; color: hsl(0 0% 100% / .7); }

        /* ============================================================
           SECTION COMMON
        ============================================================ */
        .section-label {
            display: inline-block;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--clr-primary-glow);
            margin-bottom: 1rem;
        }
        .section-heading {
            font-family: var(--font-display);
            font-size: clamp(2rem, 4vw, 3.2rem);
            color: var(--clr-fg);
            line-height: 1.12;
        }
        .section-heading em { font-style: italic; color: var(--clr-primary); }

        /* ============================================================
           TENTANG
        ============================================================ */
        .tentang {
            padding: 7rem 0;
            background: var(--grad-earth);
        }
        .tentang-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        .tentang-img-wrap {
            position: relative;
        }
        .tentang-img-glow {
            position: absolute;
            inset: -1rem;
            background: var(--grad-leaf);
            border-radius: var(--radius-2xl);
            opacity: .18;
            filter: blur(32px);
        }
        .tentang-img {
            position: relative;
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-elegant);
            width: 100%;
            height: 480px;
            object-fit: cover;
        }
        .tentang-badge {
            position: absolute;
            bottom: -1.5rem;
            right: -1.5rem;
            background: var(--clr-card);
            border-radius: 1rem;
            padding: 1.1rem 1.25rem;
            box-shadow: var(--shadow-elegant);
            border: 1px solid var(--clr-border);
            display: flex;
            align-items: center;
            gap: .85rem;
        }
        .tentang-badge-icon {
            display: grid;
            place-items: center;
            height: 48px; width: 48px;
            border-radius: .75rem;
            background: var(--clr-accent);
            color: var(--clr-primary);
        }
        .tentang-badge-title { font-family: var(--font-display); font-size: 1.1rem; }
        .tentang-badge-sub { font-size: .8rem; color: var(--clr-muted); }

        .tentang-body p { color: var(--clr-muted); font-size: 1.05rem; line-height: 1.75; margin-bottom: 1.25rem; }
        .tentang-body p strong { color: var(--clr-fg); }
        .tentang-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1.5rem; }
        .tentang-card {
            border: 1px solid var(--clr-border);
            background: var(--clr-card);
            border-radius: 1rem;
            padding: 1.25rem;
        }
        .tentang-card .card-icon { color: var(--clr-primary-glow); margin-bottom: .75rem; }
        .tentang-card .card-title { font-family: var(--font-display); font-size: 1rem; margin-bottom: .25rem; }
        .tentang-card .card-desc { font-size: .85rem; color: var(--clr-muted); }

        @media (max-width: 767px) {
            .tentang-grid { grid-template-columns: 1fr; }
            .tentang-badge { right: .5rem; bottom: -.75rem; }
        }

        /* ============================================================
           MATERIAL
        ============================================================ */
        .material { padding: 7rem 0; background: var(--clr-bg); }
        .material-header { max-width: 620px; margin-bottom: 3.5rem; }
        .material-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
        .material-card {
            border-radius: 1.5rem;
            overflow: hidden;
            background: var(--grad-card);
            border: 1px solid var(--clr-border);
            box-shadow: var(--shadow-soft);
            transition: transform var(--transition), box-shadow var(--transition);
        }
        .material-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-elegant); }
        .material-thumb {
            position: relative;
            height: 220px;
            overflow: hidden;
        }
        .material-thumb img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform .7s var(--transition);
        }
        .material-card:hover .material-thumb img { transform: scale(1.1); }
        .material-num {
            position: absolute;
            top: 1rem; left: 1rem;
            background: hsl(0 0% 100% / .95);
            backdrop-filter: blur(4px);
            color: var(--clr-primary);
            font-weight: 700;
            font-size: .75rem;
            letter-spacing: .06em;
            padding: .35rem .75rem;
            border-radius: 9999px;
        }
        .material-body { padding: 1.5rem; }
        .material-code { font-family: var(--font-display); font-size: 2rem; color: var(--clr-primary); margin-bottom: .2rem; }
        .material-name { font-size: .85rem; font-weight: 600; color: var(--clr-fg); margin-bottom: .6rem; }
        .material-desc { font-size: .83rem; color: var(--clr-muted); line-height: 1.6; }

        @media (max-width: 1023px) { .material-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 599px)  { .material-grid { grid-template-columns: 1fr; } }

        /* ============================================================
           LAYANAN
        ============================================================ */
        .layanan { padding: 7rem 0; background: var(--clr-secondary); }
        .layanan-top {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: end;
            margin-bottom: 3.5rem;
        }
        .layanan-top p { font-size: 1.05rem; color: var(--clr-muted); line-height: 1.75; }
        .layanan-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }
        .layanan-card {
            position: relative;
            border-radius: 1.5rem;
            background: var(--clr-card);
            padding: 1.75rem;
            border: 1px solid var(--clr-border);
            transition: border-color var(--transition);
            overflow: hidden;
        }
        .layanan-card:hover { border-color: hsl(150 60% 42% / .4); }
        .layanan-num {
            position: absolute;
            top: 1.5rem; right: 1.5rem;
            font-family: var(--font-display);
            font-size: 3.2rem;
            color: var(--clr-accent);
            line-height: 1;
            transition: color var(--transition);
        }
        .layanan-card:hover .layanan-num { color: hsl(150 60% 42% / .3); }
        .layanan-icon {
            display: grid;
            place-items: center;
            height: 56px; width: 56px;
            border-radius: 1rem;
            background: var(--grad-leaf);
            color: #fff;
            box-shadow: var(--shadow-soft);
            margin-bottom: 1.5rem;
        }
        .layanan-title { font-family: var(--font-display); font-size: 1.2rem; margin-bottom: .5rem; }
        .layanan-desc { font-size: .85rem; color: var(--clr-muted); line-height: 1.65; }

        .layanan-imgs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-top: 3.5rem;
        }
        .layanan-imgs img {
            border-radius: 1.5rem;
            width: 100%;
            height: 320px;
            object-fit: cover;
            box-shadow: var(--shadow-soft);
        }

        @media (max-width: 1023px) {
            .layanan-top { grid-template-columns: 1fr; }
            .layanan-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 599px) {
            .layanan-grid { grid-template-columns: 1fr; }
            .layanan-imgs { grid-template-columns: 1fr; }
        }

        /* ============================================================
           JAM BUKA
        ============================================================ */
        .jam { padding: 7rem 0; background: var(--clr-bg); }
        .jam-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }
        .jam-highlight {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            border-radius: 1rem;
            background: var(--clr-accent);
            margin-top: 2rem;
        }
        .jam-highlight-icon {
            display: grid;
            place-items: center;
            height: 48px; width: 48px;
            border-radius: .75rem;
            background: var(--clr-primary);
            color: #fff;
            flex-shrink: 0;
        }
        .jam-highlight-title { font-weight: 600; }
        .jam-highlight-sub { font-size: .85rem; color: var(--clr-muted); }

        .jam-table {
            border-radius: 1.5rem;
            overflow: hidden;
            background: var(--grad-card);
            border: 1px solid var(--clr-border);
            box-shadow: var(--shadow-elegant);
        }
        .jam-table-head {
            padding: 1.25rem 1.5rem;
            background: var(--grad-leaf);
            color: #fff;
        }
        .jam-table-head h3 { font-family: var(--font-display); font-size: 1.15rem; }
        .jam-table-head span { font-size: .8rem; opacity: .8; }
        .jam-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--clr-border);
        }
        .jam-day { font-weight: 500; }
        .jam-time { font-size: .9rem; font-weight: 700; color: var(--clr-primary-glow); }
        .jam-time.closed { color: var(--clr-destructive); }

        @media (max-width: 767px) { .jam-grid { grid-template-columns: 1fr; } }

        /* ============================================================
           KONTAK / CTA
        ============================================================ */
        .kontak { padding: 7rem 0; background: var(--grad-earth); }
        .kontak-card {
            border-radius: 2.5rem;
            overflow: hidden;
            background: var(--clr-primary);
            color: #fff;
            box-shadow: var(--shadow-elegant);
            position: relative;
        }
        .kontak-bg-img {
            position: absolute;
            inset: 0;
            width: 100%; height: 100%;
            object-fit: cover;
            opacity: .18;
        }
        .kontak-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--clr-primary) 0%, hsl(150 55% 28% / .95) 60%, hsl(150 60% 42% / .5) 100%);
        }
        .kontak-inner {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            padding: 4rem;
        }
        .kontak-section-label { color: hsl(150 60% 72%); }
        .kontak-card h2 {
            font-family: var(--font-display);
            font-size: clamp(2rem, 4vw, 3rem);
            color: #fff;
            line-height: 1.1;
            margin-bottom: 1.25rem;
        }
        .kontak-card .lead { color: hsl(0 0% 100% / .85); font-size: 1.05rem; line-height: 1.7; margin-bottom: 2rem; max-width: 420px; }

        .kontak-links { display: flex; flex-direction: column; gap: 1rem; }
        .kontak-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 1rem;
            background: hsl(0 0% 100% / .1);
            border: 1.5px solid hsl(0 0% 100% / .15);
            backdrop-filter: blur(6px);
            transition: background var(--transition);
        }
        .kontak-link:hover { background: hsl(0 0% 100% / .18); }
        .kontak-link-icon {
            display: grid;
            place-items: center;
            height: 48px; width: 48px;
            border-radius: .75rem;
            background: var(--clr-primary-glow);
            color: #fff;
            flex-shrink: 0;
        }
        .kontak-link-lbl { font-size: .7rem; text-transform: uppercase; letter-spacing: .09em; opacity: .7; }
        .kontak-link-val { font-family: var(--font-display); font-size: 1.25rem; }

        .kontak-btns { display: flex; flex-wrap: wrap; gap: .75rem; margin-top: 2rem; }

        .kontak-map {
            border-radius: 1rem;
            overflow: hidden;
            border: 1.5px solid hsl(0 0% 100% / .15);
            min-height: 420px;
        }
        .kontak-map iframe { width: 100%; height: 100%; border: none; display: block; }

        @media (max-width: 1023px) {
            .kontak-inner { grid-template-columns: 1fr; padding: 2.5rem; }
            .kontak-map { min-height: 340px; }
        }

        /* ============================================================
           FOOTER
        ============================================================ */
        .site-footer {
            background: var(--clr-primary);
            color: hsl(0 0% 100% / .75);
            padding: 3rem 0;
        }
        .footer-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }
        @media (min-width: 768px) {
            .footer-inner {
                flex-direction: row;
                justify-content: space-between;
            }
        }
        .footer-logo { display: flex; align-items: center; gap: .75rem; }
        .footer-logo-icon {
            display: grid;
            place-items: center;
            height: 40px; width: 40px;
            border-radius: .75rem;
            background: hsl(0 0% 100% / .12);
        }
        .footer-logo-icon svg { color: hsl(150 60% 72%); }
        .footer-name { font-family: var(--font-display); font-size: 1.05rem; color: #fff; }
        .footer-sub { font-size: .75rem; }
        .footer-copy { font-size: .85rem; text-align: center; }

        /* ============================================================
           ANIMATIONS
        ============================================================ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

{{-- ================================================================
     DATA (in a real Laravel app these come from controller / config)
================================================================ --}}
@php
    $phone1 = '081261834545';
    $phone2 = '085823349268';
    $address = 'Jl. Bailang Raya, Bailang, Kec. Bunaken, Kota Manado, Sulawesi Utara';
    $mapsQuery = '1.5232990627986893, 124.86356772200199';

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

{{-- ================================================================
     HEADER
================================================================ --}}
{{-- ================================================================
     HEADER
================================================================ --}}
<header class="site-header header-transparent" id="site-header">
    <div class="container">
        <div class="header-inner">
            {{-- Logo --}}
            <a href="#top" class="logo">
                <span class="logo-icon">
                    <i data-lucide="leaf" style="width:20px;height:20px;"></i>
                </span>
                <div>
                    <div class="logo-name">Bank Sampah Buha</div>
                    <div class="logo-sub">Recycle Manado</div>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <nav>
                <ul class="nav-links">
                    @foreach ($navLinks as $link)
                        <li>
                            <a href="{{ $link['href'] }}">{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            {{-- CTA --}}
            <div class="header-cta" style="display: flex; align-items: center; gap: 0.75rem;">
                {{-- TOMBOL LOGIN -- TAMBAHKAN INI --}}
                <a href="/login" class="btn btn-glass" style="padding: 0.5rem 1.25rem; font-size: 0.85rem;">
                    <i data-lucide="log-in" style="width:16px;height:16px;"></i>
                    Login Staff
                </a>
                
                <a href="tel:{{ $phone1 }}" class="btn btn-hero">
                    <i data-lucide="phone" style="width:16px;height:16px;"></i>
                    Hubungi Kami
                </a>
            </div>

            {{-- Mobile hamburger --}}
            <button class="menu-toggle" id="menu-toggle" aria-label="Menu">
                <i data-lucide="menu" id="menu-icon" style="width:24px;height:24px;"></i>
            </button>
        </div>
    </div>

    {{-- Mobile Nav --}}
    <div class="mobile-nav" id="mobile-nav">
        @foreach ($navLinks as $link)
            <a href="{{ $link['href'] }}">{{ $link['label'] }}</a>
        @endforeach
        
        {{-- TOMBOL LOGIN MOBILE -- TAMBAHKAN INI --}}
        <a href="/admin/login" class="btn btn-glass" style="margin-top: 0.5rem; justify-content: center;">
            <i data-lucide="log-in" style="width:16px;height:16px;"></i>
            Login Staff
        </a>
        
        <a href="tel:{{ $phone1 }}" class="btn btn-hero" style="margin-top:0.5rem;">
            <i data-lucide="phone" style="width:16px;height:16px;"></i>
            Hubungi Kami
        </a>
    </div>
</header>

{{-- ================================================================
     HERO
================================================================ --}}
<section id="top" class="hero">
    <img
    src="{{ asset('images/hero-nature.jpg') }}"
    alt="Hutan tropis hijau melambangkan keberlanjutan"
    class="hero-bg"
    fetchpriority="high"
/>
    <div class="hero-overlay"></div>
    <div class="hero-overlay2"></div>

    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <i data-lucide="sprout" style="width:16px;height:16px;"></i>
                <span>Daur Ulang untuk Manado yang Lebih Hijau</span>
            </div>


            
            <h1>
                Sampah plastik Anda,
                <em>sumber daya</em> kami.
            </h1>

            <p class="hero-lead">
                Bank Sampah Buha membeli segala jenis plastik — PET, PP, HDPE, dan
                LDPE. Setor di Bailang, Manado dan jadilah bagian dari perubahan.
            </p>

            <div class="hero-btns">
                <a href="#kontak" class="btn btn-hero btn-xl">
                    Setor Sekarang
                    <i data-lucide="arrow-right" style="width:20px;height:20px;"></i>
                </a>
                <a href="#material" class="btn btn-glass btn-xl">Lihat Material</a>
            </div>

            <div class="hero-stats">
                @foreach ([['v' => '4', 'l' => 'Jenis Plastik'], ['v' => '6', 'l' => 'Hari/Minggu'], ['v' => '100%', 'l' => 'Daur Ulang']] as $stat)
                    <div class="hero-stat">
                        <div class="hero-stat-val">{{ $stat['v'] }}</div>
                        <div class="hero-stat-lbl">{{ $stat['l'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     TENTANG
================================================================ --}}
<section id="tentang" class="tentang">
    <div class="container">
        <div class="tentang-grid">
            {{-- Image --}}
            <div class="tentang-img-wrap">
                <div class="tentang-img-glow"></div>
                <img
                    src="{{ asset('images/facility-yard.jpg') }}"
                    alt="Halaman fasilitas Bank Sampah Buha di Bailang Manado"
                    loading="lazy"
                    class="tentang-img"
                />
                <div class="tentang-badge">
                    <div class="tentang-badge-icon">
                        <i data-lucide="recycle" style="width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="tentang-badge-title">Sejak Tahun</div>
                        <div class="tentang-badge-sub">Melayani Manado</div>
                    </div>
                </div>
            </div>

            {{-- Copy --}}
            <div class="tentang-body">
                <span class="section-label">Tentang Kami</span>
                <h2 class="section-heading" style="margin-bottom:1.5rem;">
                    Mitra terpercaya untuk
                    <em>daur ulang plastik</em>
                    di Sulawesi Utara.
                </h2>
                <p>
                    Bank Sampah Buha (Recycle Manado) berlokasi di Bailang, Kota Manado.
                    Kami bergerak di pengumpulan, pemilahan, dan pengolahan plastik bekas
                    menjadi material bernilai ekonomis.
                </p>
                <p>
                    Komitmen kami sederhana —
                    <strong>membeli segala jenis plastik</strong>
                    dari masyarakat, memberi harga yang adil, dan memastikan setiap
                    kilogram diolah dengan benar.
                </p>

                <div class="tentang-cards">
                    <div class="tentang-card">
                        <div class="card-icon">
                            <i data-lucide="leaf" style="width:28px;height:28px;"></i>
                        </div>
                        <div class="card-title">Ramah Lingkungan</div>
                        <div class="card-desc">Mengurangi sampah TPA &amp; laut.</div>
                    </div>
                    <div class="tentang-card">
                        <div class="card-icon">
                            <i data-lucide="truck" style="width:28px;height:28px;"></i>
                        </div>
                        <div class="card-title">Setor Mudah</div>
                        <div class="card-desc">Buka 6 hari, lokasi strategis.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     MATERIAL
================================================================ --}}
<section id="material" class="material">
    <div class="container">
        <div class="material-header">
            <span class="section-label">Material yang Kami Beli</span>
            <h2 class="section-heading">Segala jenis plastik, satu tempat.</h2>
        </div>

        <div class="material-grid">
            @foreach ($materials as $i => $m)
                <article class="material-card">
                    <div class="material-thumb">
                        <img
                            src="{{ $m['image'] }}"
                            alt="Plastik {{ $m['code'] }} - {{ $m['name'] }}"
                            loading="lazy"
                        />
                        <span class="material-num">#{{ $i + 1 }}</span>
                    </div>
                    <div class="material-body">
                        <div class="material-code">{{ $m['code'] }}</div>
                        <div class="material-name">{{ $m['name'] }}</div>
                        <p class="material-desc">{{ $m['desc'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ================================================================
     LAYANAN
================================================================ --}}
<section id="layanan" class="layanan">
    <div class="container">
        <div class="layanan-top">
            <div>
                <span class="section-label">Cara Kerja Kami</span>
                <h2 class="section-heading">
                    Dari setoran Anda
                    <em>menjadi material baru.</em>
                </h2>
            </div>
            <p>
                Kami menjalankan proses lengkap — mulai dari pengumpulan,
                pemilahan, hingga pengolahan menjadi pelet plastik berkualitas
                untuk industri.
            </p>
        </div>

        <div class="layanan-grid">
            @foreach ($services as $i => $s)
                <div class="layanan-card">
                    <div class="layanan-num">0{{ $i + 1 }}</div>
                    <div class="layanan-icon">
                        <i data-lucide="{{ $s['icon'] }}" style="width:24px;height:24px;"></i>
                    </div>
                    <h3 class="layanan-title">{{ $s['title'] }}</h3>
                    <p class="layanan-desc">{{ $s['desc'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="layanan-imgs">
            <img
                src="{{ asset('images/facility-interior.jpg') }}"
                alt="Interior fasilitas pemilahan plastik"
                loading="lazy"
            />
            <img
                src="{{ asset('images/collection-bags.jpg') }}"
                alt="Plastik PET terkumpul siap diolah"
                loading="lazy"
            />
        </div>
    </div>
</section>

{{-- ================================================================
     JAM BUKA
================================================================ --}}
<section id="jam" class="jam">
    <div class="container">
        <div class="jam-grid">
            {{-- Left --}}
            <div>
                <span class="section-label">Jam Operasional</span>
                <h2 class="section-heading" style="margin-bottom:1.25rem;">
                    Datang langsung ke
                    <em>lokasi kami.</em>
                </h2>
                <p style="color:var(--clr-muted);font-size:1.05rem;line-height:1.75;">
                    Kami buka enam hari seminggu untuk melayani setoran sampah plastik
                    Anda. Hari Minggu kami tutup.
                </p>
                <div class="jam-highlight">
                    <div class="jam-highlight-icon">
                        <i data-lucide="clock" style="width:24px;height:24px;"></i>
                    </div>
                    <div>
                        <div class="jam-highlight-title">Setiap hari kerja</div>
                        <div class="jam-highlight-sub">Pukul 08.00 — 17.00 WITA</div>
                    </div>
                </div>
            </div>

            {{-- Right: schedule table --}}
            <div class="jam-table">
                <div class="jam-table-head">
                    <h3>Jadwal Mingguan</h3>
                    <span>Bank Sampah Buha</span>
                </div>
                @foreach ($hours as $h)
                    <div class="jam-row">
                        <span class="jam-day">{{ $h['day'] }}</span>
                        <span class="jam-time {{ $h['closed'] ? 'closed' : '' }}">
                            {{ $h['time'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     KONTAK / CTA
================================================================ --}}
<section id="kontak" class="kontak">
    <div class="container">
        <div class="kontak-card">
            <img
                src="{{ asset('images/hero-nature.jpg') }}"
                alt=""
                aria-hidden="true"
                loading="lazy"
                class="kontak-bg-img"
            />
            <div class="kontak-overlay"></div>

            <div class="kontak-inner">
                {{-- Left: contact info --}}
                <div>
                    <span class="section-label kontak-section-label">Hubungi Kami</span>
                    <h2>Siap setor plastik Anda hari ini?</h2>
                    <p class="lead">
                        Hubungi kami via telepon atau datang langsung ke lokasi.
                        Tim kami siap membantu.
                    </p>

                    <div class="kontak-links">
                        <a href="tel:{{ $phone1 }}" class="kontak-link">
                            <div class="kontak-link-icon">
                                <i data-lucide="phone" style="width:20px;height:20px;"></i>
                            </div>
                            <div>
                                <div class="kontak-link-lbl">Telepon Utama</div>
                                <div class="kontak-link-val">0812-6183-4545</div>
                            </div>
                        </a>

                        <a href="tel:{{ $phone2 }}" class="kontak-link">
                            <div class="kontak-link-icon">
                                <i data-lucide="phone" style="width:20px;height:20px;"></i>
                            </div>
                            <div>
                                <div class="kontak-link-lbl">Telepon Alternatif</div>
                                <div class="kontak-link-val">0858-2334-9268</div>
                            </div>
                        </a>

                        <div class="kontak-link" style="cursor:default;">
                            <div class="kontak-link-icon">
                                <i data-lucide="map-pin" style="width:20px;height:20px;"></i>
                            </div>
                            <div>
                                <div class="kontak-link-lbl">Alamat</div>
                                <div style="font-size:.95rem;line-height:1.5;">{{ $address }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="kontak-btns">
                        <a href="tel:{{ $phone1 }}" class="btn btn-hero btn-xl">
                            <i data-lucide="phone" style="width:20px;height:20px;"></i>
                            Telepon Sekarang
                        </a>
                        <a
                            href="https://www.google.com/maps/search/?api=1&query={{ $mapsQuery }}"
                            target="_blank"
                            rel="noreferrer"
                            class="btn btn-glass btn-xl"
                        >
                            <i data-lucide="map-pin" style="width:20px;height:20px;"></i>
                            Buka di Maps
                        </a>
                    </div>
                </div>

                {{-- Right: Google Maps embed --}}
                <div class="kontak-map">
                    <iframe
                        title="Lokasi Bank Sampah Buha di Bailang, Manado"
                        src="https://www.google.com/maps?q={{ $mapsQuery }}&output=embed"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================================================================
     FOOTER
================================================================ --}}
<footer class="site-footer">
    <div class="container">
        <div class="footer-inner">
            <div class="footer-logo">
                <div class="footer-logo-icon">
                    <i data-lucide="leaf" style="width:20px;height:20px;"></i>
                </div>
                <div>
                    <div class="footer-name">Bank Sampah Buha</div>
                    <div class="footer-sub">Recycle Manado · Bailang</div>
                </div>
            </div>
            <p class="footer-copy">
                © {{ date('Y') }} Bank Sampah Buha. Membangun Manado yang lebih hijau.
            </p>
        </div>
    </div>
</footer>

{{-- ================================================================
     JAVASCRIPT
================================================================ --}}
<script>
    // Init Lucide icons
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();

        // ---- Scroll-aware header ----
        const header = document.getElementById('site-header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 30) {
                header.classList.add('scrolled');
                header.classList.remove('header-transparent');
            } else {
                header.classList.remove('scrolled');
                header.classList.add('header-transparent');
            }
        }, { passive: true });

        // ---- Mobile menu toggle ----
        const toggle   = document.getElementById('menu-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        const menuIcon  = document.getElementById('menu-icon');

        toggle.addEventListener('click', () => {
            const isOpen = mobileNav.classList.toggle('open');
            menuIcon.setAttribute('data-lucide', isOpen ? 'x' : 'menu');
            lucide.createIcons();          // re-render changed icon
        });

        // Close mobile nav when a link is clicked
        mobileNav.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                mobileNav.classList.remove('open');
                menuIcon.setAttribute('data-lucide', 'menu');
                lucide.createIcons();
            });
        });
    });
</script>

</body>
</html>