<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setel Ulang Password — Perpustakan Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-[#0f172a] text-slate-300 min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    <!-- Background Decoration -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px]"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-violet-600/20 rounded-full blur-[120px]"></div>

    <div class="max-w-md w-full relative z-10 animate-fade-in-up">
        
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-white mb-2">Password Baru</h1>
            <p class="text-slate-500 text-sm">Silakan masukkan password baru Anda di bawah ini.</p>
        </div>

        <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[32px] p-8 shadow-2xl">
            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Email</label>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly
                           class="w-full bg-black/20 border border-white/5 rounded-2xl py-4 px-6 text-slate-500 text-sm outline-none">
                    @error('email')
                        <p class="text-red-400 text-[10px] font-bold mt-1 ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Password Baru</label>
                    <div class="relative">
                        <i class="fas fa-key absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="password" name="password" required autofocus
                               class="w-full bg-black/20 border border-white/5 rounded-2xl py-4 pl-12 pr-6 text-white text-sm focus:border-indigo-500 outline-none transition-all"
                               placeholder="Min. 8 karakter">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <i class="fas fa-shield-check absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input type="password" name="password_confirmation" required
                               class="w-full bg-black/20 border border-white/5 rounded-2xl py-4 pl-12 pr-6 text-white text-sm focus:border-indigo-500 outline-none transition-all"
                               placeholder="Ulangi password baru">
                    </div>
                </div>

                <button type="submit" 
                        class="w-full py-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/25 transition-all hover:-translate-y-1">
                    Simpan Password Baru
                </button>
            </form>
        </div>

    </div>

</body>
</html>
