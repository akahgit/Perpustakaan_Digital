<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Perpustakan Digital')</title>

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    
    <!-- Font Awesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite + Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine JS untuk interaksi (Mobile Menu, Dropdown) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        
        /* Custom Scrollbar agar lebih gelap */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #09090b; }
        ::-webkit-scrollbar-thumb { background: #27272a; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #3f3f46; }
    </style>
    
    @stack('styles')
</head>

<!-- Body dengan background gelap sesuai design -->
<body class="bg-[#0f172a] text-white antialiased selection:bg-indigo-500/30 selection:text-indigo-200" 
      x-data="{ mobileMenuOpen: false, profileDropdownOpen: false }">

    <!-- TOAST NOTIFICATION SYSTEM -->
    @include('components.toast-notification')
    @include('components.confirm-modal')

    <!-- NAVBAR ATAS (Fixed Top) -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#0f172a]/80 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- 1. LOGO (Kiri) -->
                <div class="flex items-center gap-3 flex-shrink-0">
                    <img src="{{ asset('asset/logo.jpeg') }}"
                         alt="Logo Perpustakan Digital"
                         class="w-10 h-10 rounded-xl object-cover shadow-lg shadow-slate-900/30 ring-1 ring-white/10">
                    <div class="leading-tight">
                        <div class="font-bold text-lg tracking-tight">Perpustakan<span class="text-purple-400">Digital</span></div>
                    </div>
                </div>

                <!-- 2. MENU TENGAH (Desktop Only) -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('home') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('home') ? 'text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Beranda
                    </a>
                    <a href="{{ route('katalog') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('katalog') ? 'text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Katalog Buku
                    </a>
                    <a href="{{ route('peminjaman') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('peminjaman') ? 'text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Peminjaman Saya
                    </a>
                    <a href="{{ route('riwayat') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('riwayat') ? 'text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Riwayat
                    </a>
                    <a href="{{ route('tentang') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('tentang') ? 'text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Tentang
                    </a>
                    <a href="{{ route('kontak') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('kontak') ? 'text-white bg-white/10' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Kontak Kami
                    </a>
                </div>

                <!-- 3. PROFIL & LOGOUT (Kanan) -->
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <!-- Profile Trigger -->
                        <div class="relative">
                            <button @click="profileDropdownOpen = !profileDropdownOpen" 
                                    class="flex items-center gap-3 pl-2 pr-1 py-1.5 rounded-full hover:bg-white/5 transition border border-transparent hover:border-white/10">
                                <div class="text-right hidden lg:block">
                                    <div class="text-sm font-semibold text-white">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-400">Anggota</div>
                                </div>
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full flex items-center justify-center text-sm font-bold shadow-lg shadow-purple-500/20 ring-2 ring-white/10">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-400 mr-1"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="profileDropdownOpen" 
                                 @click.away="profileDropdownOpen = false" 
                                 x-cloak
                                 class="absolute right-0 mt-2 w-56 bg-[#0a0a0a] border border-white/10 rounded-2xl shadow-2xl py-2 z-50 overflow-hidden">
                                
                                <div class="px-4 py-3 border-b border-white/5">
                                    <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                
                                <a href="{{ route('profil') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:text-white hover:bg-white/5 transition">
                                    <i class="fas fa-user w-5 text-center text-purple-400"></i>
                                    Profil Saya
                                </a>
                                <a href="{{ route('riwayat') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:text-white hover:bg-white/5 transition">
                                    <i class="fas fa-history w-5 text-center text-blue-400"></i>
                                    Riwayat
                                </a>
                                
                                <div class="border-t border-white/5 my-1"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition">
                                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Guest Buttons -->
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white transition">Masuk</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white text-sm font-semibold rounded-full shadow-lg shadow-purple-500/25 transition transform hover:scale-105">
                            Daftar
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button (Hamburger) -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-400 hover:text-white p-2 transition">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Slide Down) -->
        <div x-show="mobileMenuOpen" 
             @click.away="mobileMenuOpen = false"
             x-cloak
             class="md:hidden bg-[#0a0a0a] border-b border-white/10 max-h-[80vh] overflow-y-auto">
            <div class="px-4 py-6 space-y-2">
                <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('home') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/30' : 'text-gray-300 hover:bg-white/5' }}">Beranda</a>
                <a href="{{ route('katalog') }}" class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('katalog') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/30' : 'text-gray-300 hover:bg-white/5' }}">Katalog Buku</a>
                <a href="{{ route('peminjaman') }}" class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('peminjaman') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/30' : 'text-gray-300 hover:bg-white/5' }}">Peminjaman Saya</a>
                <a href="{{ route('riwayat') }}" class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('riwayat') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/30' : 'text-gray-300 hover:bg-white/5' }}">Riwayat</a>
                <a href="{{ route('tentang') }}" class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('tentang') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/30' : 'text-gray-300 hover:bg-white/5' }}">Tentang</a>
                <a href="{{ route('kontak') }}" class="block px-4 py-3 rounded-xl text-base font-medium {{ request()->routeIs('kontak') ? 'bg-purple-600/20 text-purple-300 border border-purple-500/30' : 'text-gray-300 hover:bg-white/5' }}">Kontak Kami</a>
                
                <div class="border-t border-white/10 my-4 pt-4">
                    @auth
                        <div class="flex items-center gap-3 px-4 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full flex items-center justify-center font-bold">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            <div>
                                <div class="font-semibold">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-400">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 bg-red-500/10 text-red-400 rounded-xl font-medium">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full px-4 py-3 border border-white/10 text-center rounded-xl mb-3">Masuk</a>
                        <a href="{{ route('register') }}" class="block w-full px-4 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-center rounded-xl font-semibold">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT WRAPPER -->
    <!-- pt-20 untuk kompensasi navbar fixed height (20 = 5rem = 80px) -->
    <main class="pt-20 min-h-screen flex flex-col">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#020202] border-t border-white/5 py-12 mt-auto">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book-open text-white text-sm"></i>
                        </div>
                        <span class="font-bold text-lg">Perpus<span class="text-purple-400">Digital</span></span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Sistem perpustakaan digital modern untuk memudahkan akses buku bagi semua orang.
                    </p>
                </div>

                <!-- Links 1 -->
                <div>
                    <h4 class="font-bold text-white mb-4">Navigasi</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-purple-400 transition">Beranda</a></li>
                        <li><a href="{{ route('katalog') }}" class="hover:text-purple-400 transition">Katalog Buku</a></li>
                        <li><a href="{{ route('peminjaman') }}" class="hover:text-purple-400 transition">Peminjaman</a></li>
                        <li><a href="{{ route('riwayat') }}" class="hover:text-purple-400 transition">Riwayat</a></li>
                        <li><a href="{{ route('kontak') }}" class="hover:text-purple-400 transition">Kontak Kami</a></li>
                    </ul>
                </div>

                <!-- Links 2 -->
                <div>
                    <h4 class="font-bold text-white mb-4">Informasi</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('tentang') }}" class="hover:text-purple-400 transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition">FAQ</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-bold text-white mb-4">Kontak</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-purple-400 mt-1"></i>
                            <span>Jl. Pendidikan No. 1, Indonesia</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone text-purple-400"></i>
                            <span>(021) 1234-5678</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-purple-400"></i>
                            <span>info@perpusdigital.id</span>
                        </li>
                    </ul>
                    <div class="flex gap-3 mt-6">
                        <a href="#" class="w-8 h-8 bg-white/5 hover:bg-purple-500/20 rounded-lg flex items-center justify-center transition"><i class="fab fa-instagram text-gray-400 hover:text-purple-400"></i></a>
                        <a href="#" class="w-8 h-8 bg-white/5 hover:bg-blue-500/20 rounded-lg flex items-center justify-center transition"><i class="fab fa-twitter text-gray-400 hover:text-blue-400"></i></a>
                        <a href="#" class="w-8 h-8 bg-white/5 hover:bg-blue-600/20 rounded-lg flex items-center justify-center transition"><i class="fab fa-facebook text-gray-400 hover:text-blue-600"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-white/5 pt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Perpustakan Digital. All rights reserved.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
