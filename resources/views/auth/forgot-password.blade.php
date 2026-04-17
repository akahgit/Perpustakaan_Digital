<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — Perpustakan Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#0f172a] text-slate-300 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    <!-- Background Decoration -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px]"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-violet-600/20 rounded-full blur-[120px]"></div>

    <div class="max-w-md w-full relative z-10 animate-fade-in-up">
        
        <!-- Logo & Title -->
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl shadow-indigo-500/20">
                <i class="fas fa-lock-open text-3xl text-white"></i>
            </div>
            <h1 class="text-3xl font-black text-white mb-2">Lupa Password?</h1>
            <p class="text-slate-500 text-sm">Jangan khawatir! Beritahu kami email Anda, dan kami akan mengirimkan link untuk mengatur ulang password Anda.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-start gap-3 animate-scale-in">
                <i class="fas fa-check-circle text-emerald-400 mt-0.5"></i>
                <p class="text-xs text-emerald-200/80 leading-relaxed">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[32px] p-8 shadow-2xl">
            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Email Terdaftar</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="email" name="email" required autofocus
                               class="w-full bg-black/20 border border-white/5 rounded-2xl py-4 pl-12 pr-6 text-white text-sm focus:border-indigo-500 outline-none transition-all placeholder:text-slate-600"
                               placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <p class="text-red-400 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/25 transition-all hover:-translate-y-1 flex items-center justify-center gap-3">
                    Kirim Link Reset
                    <i class="fas fa-paper-plane text-xs opacity-50"></i>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-white/5 text-center">
                <p class="text-xs text-slate-500">
                    Ingat password Anda? 
                    <a href="{{ route('login') }}" class="text-indigo-400 font-bold hover:text-indigo-300 transition ml-1">Kembali Login</a>
                </p>
            </div>
        </div>

    </div>

</body>
</html>
