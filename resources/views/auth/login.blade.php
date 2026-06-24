{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="theme-color" content="#059669">
    <meta name="description" content="Login - Bank Sampah Buha Recycle Manado">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Login - Bank Sampah Buha</title>
    
    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#059669',
                        'primary-dark': '#047857',
                        'primary-light': '#d1fae5',
                    }
                }
            }
        }
    </script>
    
    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest" defer></script>
    
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
        }
        
        .bg-mesh {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.08) 0, transparent 50%), 
                radial-gradient(at 100% 100%, rgba(5, 150, 105, 0.08) 0, transparent 50%),
                radial-gradient(at 50% 0%, rgba(16, 185, 129, 0.04) 0, transparent 50%);
        }
        
        /* Animasi */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out both;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out both;
        }
        
        .animate-slide-left {
            animation: slideInLeft 0.5s ease-out both;
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        
        /* Glass effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Focus ring custom */
        .input-focus:focus {
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1);
        }
        
        /* Touch-friendly */
        @media (hover: none) and (pointer: coarse) {
            button, a, input {
                min-height: 44px;
            }
        }
        
        /* Print */
        @media print {
            .bg-mesh { background: white; }
            body { min-height: auto; }
        }
        
        /* Smooth scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    </style>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-3 sm:p-4 md:p-6">

    {{-- Main Card Container --}}
    <div class="w-full max-w-5xl bg-white rounded-2xl sm:rounded-3xl shadow-xl sm:shadow-2xl overflow-hidden animate-fade-in-up">
        <div class="grid lg:grid-cols-2">
            
            {{-- ==================== LEFT: BRANDING PANEL ==================== --}}
            <div class="hidden lg:flex bg-gradient-to-br from-emerald-600 to-emerald-700 p-8 xl:p-12 flex-col justify-between text-white relative overflow-hidden">
                
                {{-- Decorative circles --}}
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-emerald-500 rounded-full opacity-40"></div>
                <div class="absolute top-1/3 -left-16 w-48 h-48 bg-emerald-400 rounded-full blur-3xl opacity-30"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-emerald-800 rounded-full opacity-20 transform translate-x-1/3 translate-y-1/3"></div>
                
                {{-- Content --}}
                <div class="relative z-10 animate-slide-left">
                    {{-- Logo & Brand --}}
                    <div class="flex items-center gap-3 mb-8">
                        <div class="p-2.5 bg-white/20 rounded-xl backdrop-blur-md">
                            <i data-lucide="recycle" class="w-8 h-8 text-white"></i>
                        </div>
                        <div>
                            <span class="text-lg xl:text-xl font-bold tracking-tight block leading-tight">
                                Bank Sampah Buha
                            </span>
                            <span class="text-emerald-200 text-xs xl:text-sm tracking-wide">
                                Recycle Manado
                            </span>
                        </div>
                    </div>
                    
                    {{-- Headline --}}
                    <h1 class="text-3xl xl:text-4xl font-extrabold leading-tight mb-4 tracking-tight">
                        Digitalkan Pengelolaan <br class="hidden xl:block"> Sampah Plastik.
                    </h1>
                    <p class="text-emerald-100 text-base xl:text-lg leading-relaxed max-w-sm">
                        Membangun ekosistem lingkungan yang lebih bersih melalui integrasi teknologi di Bank Sampah Buha.
                    </p>
                    
                    {{-- Features list --}}
                    <div class="mt-10 space-y-4">
                        <div class="flex items-center gap-3 text-emerald-100">
                            <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </div>
                            <span class="text-sm">Akses aman & terenkripsi</span>
                        </div>
                        <div class="flex items-center gap-3 text-emerald-100">
                            <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="zap" class="w-4 h-4"></i>
                            </div>
                            <span class="text-sm">Manajemen real-time</span>
                        </div>
                        <div class="flex items-center gap-3 text-emerald-100">
                            <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="leaf" class="w-4 h-4"></i>
                            </div>
                            <span class="text-sm">Ramah lingkungan</span>
                        </div>
                    </div>
                </div>
                
                {{-- Footer --}}
                <div class="relative z-10 mt-8">
                    <p class="text-emerald-200 text-xs opacity-75">
                        © {{ date('Y') }} Bank Sampah Buha. All rights reserved.
                    </p>
                </div>
            </div>
            
            {{-- ==================== RIGHT: LOGIN FORM ==================== --}}
            <div class="p-6 sm:p-8 md:p-12 lg:p-10 xl:p-14 flex flex-col justify-center animate-fade-in delay-200">
                
                {{-- Mobile Logo (Visible only on mobile/tablet) --}}
                <div class="mb-6 lg:hidden text-center">
                    <i data-lucide="recycle" class="w-10 h-10 sm:w-12 sm:h-12 text-emerald-600 mx-auto mb-2"></i>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Bank Sampah Buha</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Recycle Manado</p>
                </div>
                
                {{-- Welcome Text --}}
                <div class="mb-6 sm:mb-8">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-1 sm:mb-2">
                        Selamat Datang 👋
                    </h2>
                    <p class="text-sm sm:text-base text-gray-500">
                        Silakan masuk untuk mengelola data sampah.
                    </p>
                </div>
                
                {{-- Error Message --}}
                @if ($errors->any())
                    <div class="mb-5 sm:mb-6 flex items-start gap-3 p-3 sm:p-4 bg-red-50 text-red-700 rounded-xl sm:rounded-2xl border border-red-100 animate-fade-in">
                        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold">Login Gagal</p>
                            <p class="text-xs sm:text-sm mt-0.5">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif
                
                {{-- Success Message (if any) --}}
                @if (session('success'))
                    <div class="mb-5 sm:mb-6 flex items-start gap-3 p-3 sm:p-4 bg-green-50 text-green-700 rounded-xl sm:rounded-2xl border border-green-100 animate-fade-in">
                        <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold">Berhasil</p>
                            <p class="text-xs sm:text-sm mt-0.5">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                
                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5">
                    @csrf
                    
                    {{-- Email Field --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                            Email Institusi
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 sm:pl-4 flex items-center text-gray-400 group-focus-within:text-emerald-600 transition-colors pointer-events-none">
                                <i data-lucide="mail" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                id="email"
                                value="{{ old('email') }}" 
                                required
                                autofocus
                                autocomplete="email"
                                class="input-focus w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 bg-gray-50 border border-gray-200 rounded-xl sm:rounded-2xl focus:bg-white focus:border-emerald-500 outline-none transition-all placeholder:text-gray-400 text-sm sm:text-base"
                                placeholder="nama@email.com"
                            >
                        </div>
                    </div>
                    
                    {{-- Password Field --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5 sm:mb-2">
                            <label for="password" class="block text-sm font-semibold text-gray-700">
                                Kata Sandi
                            </label>
                            <a href="#" class="text-xs sm:text-sm text-emerald-600 hover:text-emerald-700 font-medium transition-colors hidden sm:inline">
                                Lupa sandi?
                            </a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 sm:pl-4 flex items-center text-gray-400 group-focus-within:text-emerald-600 transition-colors pointer-events-none">
                                <i data-lucide="lock" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                required
                                autocomplete="current-password"
                                class="input-focus w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 bg-gray-50 border border-gray-200 rounded-xl sm:rounded-2xl focus:bg-white focus:border-emerald-500 outline-none transition-all placeholder:text-gray-400 text-sm sm:text-base"
                                placeholder="••••••••"
                            >
                            {{-- Toggle Password Visibility --}}
                            <button 
                                type="button"
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3.5 sm:pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                                aria-label="Tampilkan/sembunyikan kata sandi"
                            >
                                <i data-lucide="eye" id="eyeIcon" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                            </button>
                        </div>
                    </div>
                    
                    {{-- Remember Me --}}
                    <div class="flex items-center">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input 
                                type="checkbox" 
                                name="remember" 
                                class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 focus:ring-2 cursor-pointer"
                            >
                            <span class="text-sm text-gray-600 group-hover:text-emerald-600 transition-colors">
                                Ingat saya
                            </span>
                        </label>
                    </div>
                    
                    {{-- Submit Button --}}
                    <button 
                        type="submit" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold py-3.5 sm:py-4 rounded-xl sm:rounded-2xl shadow-lg shadow-emerald-200 hover:shadow-xl hover:shadow-emerald-200/50 transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5 active:translate-y-0 text-sm sm:text-base"
                    >
                        <span>Masuk ke Dashboard</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </button>
                </form>
                
                {{-- Help Link --}}
                <div class="mt-6 sm:mt-8 pt-5 sm:pt-6 border-t border-gray-100 text-center">
                    <p class="text-xs sm:text-sm text-gray-500">
                        Butuh bantuan akses? 
                        <a href="#" class="text-emerald-600 font-semibold hover:text-emerald-700 hover:underline transition-colors">
                            Hubungi Admin IT
                        </a>
                    </p>
                </div>
                
                {{-- Back to Home --}}
                <div class="mt-4 text-center">
                    <a href="{{ route('landing') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-gray-400 hover:text-emerald-600 transition-colors">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== SCRIPTS ==================== --}}
    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (togglePassword && passwordInput && eyeIcon) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Update icon
                    if (type === 'text') {
                        eyeIcon.setAttribute('data-lucide', 'eye-off');
                    } else {
                        eyeIcon.setAttribute('data-lucide', 'eye');
                    }
                    
                    // Re-render icons
                    lucide.createIcons();
                });
            }
            
            // Auto-focus email field with animation
            const emailInput = document.getElementById('email');
            if (emailInput && !emailInput.value) {
                setTimeout(() => {
                    emailInput.focus();
                }, 600);
            }
        });
        
        // Handle form submission loading state
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Memproses...</span>
            `;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
        });
    </script>

</body>
</html>