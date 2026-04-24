<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bank Sampah Buha</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-mesh {
            background-color: #f8fafc;
            background-image: radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.1) 0, transparent 50%), 
                              radial-gradient(at 100% 100%, rgba(5, 150, 105, 0.1) 0, transparent 50%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-mesh min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-5xl grid lg:grid-cols-2 bg-white rounded-3xl shadow-2xl overflow-hidden min-h-[600px]">
        
        <div class="hidden lg:flex bg-emerald-600 p-12 flex-col justify-between text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-8">
                    <div class="p-2 bg-white/20 rounded-lg backdrop-blur-md">
                        <i data-lucide="recycle" class="w-8 h-8 text-white"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight">Bank Sampah Buha Recycle Manado</span>
                </div>
                <h1 class="text-4xl font-bold leading-tight mb-4">Digitalkan Pengelolaan <br> Sampah Plastik.</h1>
                <p class="text-emerald-100 text-lg">Membangun ekosistem lingkungan yang lebih bersih melalui integrasi teknologi di Bank Sampah Buha.</p>
            </div>
            
            <div class="relative z-10">
                <div class="flex -space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-full border-2 border-emerald-600 bg-emerald-200 flex items-center justify-center text-emerald-800 text-xs font-bold">A</div>
                    <div class="w-10 h-10 rounded-full border-2 border-emerald-600 bg-emerald-300 flex items-center justify-center text-emerald-800 text-xs font-bold">B</div>
                    <div class="w-10 h-10 rounded-full border-2 border-emerald-600 bg-emerald-400 flex items-center justify-center text-emerald-800 text-xs font-bold">C</div>
                </div>
                <p class="text-sm text-emerald-100 italic">"Bergabunglah bersama ratusan warga Buha lainnya."</p>
            </div>

            <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-emerald-500 rounded-full opacity-50"></div>
            <div class="absolute top-1/2 -left-10 w-40 h-40 bg-emerald-400 rounded-full blur-3xl opacity-30"></div>
        </div>

        <div class="p-8 md:p-16 flex flex-col justify-center">
            <div class="mb-10 lg:hidden text-center">
                 <i data-lucide="recycle" class="w-12 h-12 text-emerald-600 mx-auto mb-2"></i>
                 <h2 class="text-2xl font-bold text-gray-800">Bank Sampah Buha</h2>
            </div>

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang</h2>
                <p class="text-gray-500">Silakan masuk untuk mengelola data limbah.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 flex items-center gap-3 p-4 bg-red-50 text-red-700 rounded-2xl border border-red-100 animate-pulse">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                    <p class="text-sm font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email Institusi</label>
                    <div class="group relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 group-focus-within:text-emerald-600 transition-colors">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 outline-none transition-all placeholder:text-gray-400"
                            placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi</label>
                    <div class="group relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 group-focus-within:text-emerald-600 transition-colors">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input type="password" name="password" required
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:ring-4 focus:ring-emerald-100 focus:border-emerald-500 outline-none transition-all placeholder:text-gray-400"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        <span class="text-sm text-gray-600 group-hover:text-emerald-600 transition-colors">Ingat saya</span>
                    </label>
                   
                </div>

                <button type="submit" 
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-emerald-200 transition-all flex items-center justify-center gap-2 hover:translate-y-[-2px] active:translate-y-[0]">
                    <span>Masuk Dashboard</span>
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </button>
            </form>

            <div class="mt-10 pt-8 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500">
                    Butuh bantuan akses? <a href="#" class="text-emerald-600 font-bold hover:underline">Hubungi Admin IT</a>
                </p>
            </div>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>