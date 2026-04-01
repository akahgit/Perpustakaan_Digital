@extends('layouts.guest')

@section('title', 'Masuk - Perpustakaan Digital')

@section('content')
<div class="text-center mb-8">
    <!-- Logo Icon -->
    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600/10 rounded-2xl mb-4 border border-blue-500/20 shadow-lg shadow-blue-500/10">
        <i class="fas fa-book-open text-3xl text-blue-500"></i>
    </div>
    <h2 class="text-2xl font-bold text-white mb-1">Perpustakaan Digital</h2>
    <p class="text-gray-400 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
</div>

<!-- Login Card -->
<div class="bg-[#0f172a]/80 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Username / NIS Input -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2 ml-1">Username atau NIS</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-user text-gray-500 group-focus-within:text-blue-400 transition"></i>
                </div>
                <input type="text" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}"
                       required 
                       autofocus
                       class="w-full pl-11 pr-4 py-3.5 bg-[#1e293b]/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                       placeholder="Masukkan username atau NIS">
            </div>
            @error('email')
                <p class="mt-2 text-xs text-red-400 ml-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Input -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2 ml-1">Kata Sandi</label>
            <div class="relative group" x-data="{ show: false }">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-500 group-focus-within:text-blue-400 transition"></i>
                </div>
                <input :type="show ? 'text' : 'password'"
                       name="password" 
                       id="password" 
                       required
                       class="w-full pl-11 pr-12 py-3.5 bg-[#1e293b]/50 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all"
                       placeholder="Masukkan kata sandi">
                
                <button type="button" 
                        @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-300 transition">
                    <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-2 text-xs text-red-400 ml-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-600 bg-[#1e293b] text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                <span class="text-gray-400 group-hover:text-gray-300 transition">Ingat saya</span>
            </label>
            <a href="#" class="text-blue-400 hover:text-blue-300 font-medium transition">Lupa kata sandi?</a>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full py-3.5 px-4 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white font-bold rounded-xl shadow-lg shadow-blue-500/25 transition transform hover:scale-[1.02] active:scale-[0.98]">
            Masuk
        </button>
    </form>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-700"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-[#0f172a] px-2 text-gray-500">atau</span>
        </div>
    </div>

    <!-- Register Link -->
    <p class="text-center text-sm text-gray-400">
        Belum punya akun? 
        <a href="{{ route('register') }}" class="font-bold text-blue-400 hover:text-blue-300 transition">Daftar di sini</a>
    </p>
</div>

<!-- Footer Copyright -->
<p class="mt-8 text-center text-xs text-gray-600">
    Perpustakaan Digital &copy; {{ date('Y') }}
</p>
@endsection