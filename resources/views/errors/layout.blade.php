<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terjadi Kesalahan') — Recycle Manado</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green:      #1cc88a;
            --green-dark: #17a673;
            --blue:       #4e73df;
            --red:        #e74a3b;
            --orange:     #f6c23e;
            --gray-100:   #f8f9fc;
            --gray-200:   #eaecf4;
            --gray-500:   #b7b9cc;
            --gray-700:   #5a5c69;
            --gray-900:   #1a1a2e;
            --accent:     @yield('accent-color', '#1cc88a');
            --accent-bg:  @yield('accent-bg', 'rgba(28,200,138,.08)');
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--gray-100);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow: hidden;
            position: relative;
        }

        /* Subtle grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(var(--gray-200) 1px, transparent 1px),
                linear-gradient(90deg, var(--gray-200) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: .5;
            pointer-events: none;
            z-index: 0;
        }

        /* Glowing orb */
        body::after {
            content: '';
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--accent) 0%, transparent 70%);
            opacity: .06;
            top: -150px;
            right: -150px;
            pointer-events: none;
            z-index: 0;
        }

        .card {
            position: relative;
            z-index: 1;
            background: #fff;
            border-radius: 20px;
            padding: 56px 48px;
            max-width: 560px;
            width: 100%;
            box-shadow:
                0 0 0 1px var(--gray-200),
                0 20px 60px rgba(0,0,0,.08),
                0 4px 12px rgba(0,0,0,.04);
            text-align: center;
            animation: slideUp .5s cubic-bezier(.16,1,.3,1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--accent-bg);
            color: var(--accent);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 999px;
            margin-bottom: 28px;
        }

        .code {
            font-size: clamp(72px, 15vw, 120px);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -4px;
            background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-700) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 16px;
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 12px;
        }

        .message {
            font-size: 15px;
            color: var(--gray-700);
            line-height: 1.7;
            margin-bottom: 36px;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all .2s ease;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary:hover {
            background: var(--green-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(28,200,138,.35);
            color: #fff;
            text-decoration: none;
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid var(--gray-200);
        }
        .btn-secondary:hover {
            background: var(--gray-200);
            transform: translateY(-1px);
            color: var(--gray-900);
            text-decoration: none;
        }

        .divider {
            border: none;
            border-top: 1px solid var(--gray-200);
            margin: 32px 0;
        }

        .meta {
            font-size: 12px;
            color: var(--gray-500);
        }

        .icon-wrap {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: var(--accent-bg);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .icon-wrap svg {
            width: 28px;
            height: 28px;
            color: var(--accent);
        }

        @media (max-width: 480px) {
            .card { padding: 36px 24px; }
            .actions { flex-direction: column; }
            .btn { justify-content: center; }
        }
    </style>
    @yield('extra-styles')
</head>
<body>
    <div class="card">
        @yield('content')

        <hr class="divider">
        <p class="meta">
            &copy; {{ date('Y') }} Bank Sampah Buha — Recycle Manado
            &nbsp;·&nbsp;
            <a href="{{ route('landing') }}" style="color: var(--accent); text-decoration:none;">Beranda</a>
        </p>
    </div>
</body>
</html>