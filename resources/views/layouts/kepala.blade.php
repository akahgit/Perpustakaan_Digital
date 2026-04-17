<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Kepala — Perpustakan Digital')</title>
    <meta name="description" content="Panel manajemen kepala perpustakaan digital.">

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite + Tailwind CSS v4 -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('styles')
</head>

<body class="bg-[#0b1120] text-slate-300 antialiased" x-data="{ sidebarOpen: false }">

    <!-- ════════════════════════════════════════════
         TOAST NOTIFICATION SYSTEM
         ════════════════════════════════════════════ -->
    @include('components.toast-notification')

    <div class="flex h-screen overflow-hidden">

        <!-- ════════════════════════════════════════
             SIDEBAR — KEPALA
             ════════════════════════════════════════ -->
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             x-cloak
             class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f172a] border-r border-white/5 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col">

            <!-- Logo -->
            <div class="h-[72px] flex items-center px-5 border-b border-white/5 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('asset/logo.jpeg') }}"
                         alt="Logo Perpustakan Digital"
                         class="w-9 h-9 rounded-xl object-cover shadow-lg shadow-slate-950/30 ring-1 ring-white/10 flex-shrink-0">
                    <div>
                        <div class="font-bold text-white text-sm leading-tight tracking-tight">Perpustakan Digital</div>
                        <div class="text-[10px] text-blue-400/80 font-semibold tracking-widest uppercase">Kepala</div>
                    </div>
                </div>
            </div>

            <!-- Nav Menu -->
            <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-0.5 scrollbar-dark">

                <!-- Group: Menu Utama -->
                <div class="mb-5">
                    <div class="px-3 mb-2 text-[10px] font-bold text-slate-600 uppercase tracking-[0.15em]">Panel Analitik</div>

                    <a href="{{ route('kepala.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('kepala.dashboard')
                                  ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/25'
                                  : 'text-slate-400 hover:text-slate-100 hover:bg-white/5' }} group">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ request()->routeIs('kepala.dashboard') ? 'bg-white/20' : 'bg-white/5 group-hover:bg-white/10' }} transition">
                            <i class="fas fa-gauge-high text-sm {{ request()->routeIs('kepala.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                        </div>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('kepala.laporan') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('kepala.laporan*')
                                  ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/25'
                                  : 'text-slate-400 hover:text-slate-100 hover:bg-white/5' }} group">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ request()->routeIs('kepala.laporan*') ? 'bg-white/20' : 'bg-white/5 group-hover:bg-white/10' }} transition">
                            <i class="fas fa-file-chart-column text-sm {{ request()->routeIs('kepala.laporan*') ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                        </div>
                        <span>Laporan</span>
                    </a>

                    <a href="{{ route('kepala.statistik') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('kepala.statistik*')
                                  ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/25'
                                  : 'text-slate-400 hover:text-slate-100 hover:bg-white/5' }} group">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ request()->routeIs('kepala.statistik*') ? 'bg-white/20' : 'bg-white/5 group-hover:bg-white/10' }} transition">
                            <i class="fas fa-chart-line text-sm {{ request()->routeIs('kepala.statistik*') ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                        </div>
                        <span>Statistik</span>
                    </a>

                    <a href="{{ route('kepala.aktivitas') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                               {{ request()->routeIs('kepala.aktivitas*')
                                   ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/25'
                                   : 'text-slate-400 hover:text-slate-100 hover:bg-white/5' }} group">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                     {{ request()->routeIs('kepala.aktivitas*') ? 'bg-white/20' : 'bg-white/5 group-hover:bg-white/10' }} transition">
                            <i class="fas fa-history text-sm {{ request()->routeIs('kepala.aktivitas*') ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                        </div>
                        <span>Audit Aktivitas</span>
                    </a>
                </div>

                <!-- Group: Manajemen -->
                <div class="mb-5">
                    <div class="px-3 mb-2 text-[10px] font-bold text-slate-600 uppercase tracking-[0.15em]">Manajemen</div>

                    <a href="{{ route('kepala.petugas.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('kepala.petugas*')
                                  ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/25'
                                  : 'text-slate-400 hover:text-slate-100 hover:bg-white/5' }} group">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ request()->routeIs('kepala.petugas*') ? 'bg-white/20' : 'bg-white/5 group-hover:bg-white/10' }} transition">
                            <i class="fas fa-user-tie text-sm {{ request()->routeIs('kepala.petugas*') ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                        </div>
                        <span>Daftar Petugas</span>
                    </a>

                    <a href="{{ route('kepala.profile') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('kepala.profile*')
                                  ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/25'
                                  : 'text-slate-400 hover:text-slate-100 hover:bg-white/5' }} group">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ request()->routeIs('kepala.profile*') ? 'bg-white/20' : 'bg-white/5 group-hover:bg-white/10' }} transition">
                            <i class="fas fa-user-shield text-sm {{ request()->routeIs('kepala.profile*') ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                        </div>
                        <span>Profil & Keamanan</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile (Bottom) -->
            <div class="p-3 border-t border-white/5 flex-shrink-0">
                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/3 border border-white/5 hover:bg-white/5 transition group relative">
                    <a href="{{ route('kepala.profile') }}" class="absolute inset-0 z-0"></a>
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-lg flex-shrink-0 relative z-10">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0 relative z-10">
                        <div class="text-sm font-semibold text-white truncate leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-slate-500 truncate">Kepala Perpustakaan</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="relative z-10">
                        @csrf
                        <button type="submit"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 transition"
                                title="Logout">
                            <i class="fas fa-right-from-bracket text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- ════════════════════════════════════════
             MAIN CONTENT AREA
             ════════════════════════════════════════ -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- HEADER -->
            <header class="h-[72px] bg-[#0b1120]/95 backdrop-blur-sm border-b border-white/5 flex items-center justify-between px-4 lg:px-6 z-30 flex-shrink-0">

                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="lg:hidden w-9 h-9 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition">
                        <i class="fas fa-bars text-base"></i>
                    </button>
                    <div class="hidden sm:block">
                        <h1 class="text-base font-bold text-white leading-tight tracking-tight">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-xs text-slate-500 mt-0.5">@yield('page-subtitle', 'Perpustakan Digital')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 lg:gap-5">

                    <!-- Search -->
                    <form action="{{ route('kepala.search') }}" method="GET" class="hidden md:block relative">
                        <input type="text" name="q" value="{{ request('q') }}"
                               placeholder="Cari data..."
                               class="w-52 lg:w-64 pl-9 pr-4 py-2 bg-white/5 border border-white/8 rounded-xl text-sm text-white placeholder-slate-500
                                      focus:outline-none focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/20 transition">
                        <button type="submit" class="absolute left-3 top-2.5 text-slate-500 hover:text-blue-400 transition">
                            <i class="fas fa-magnifying-glass text-xs"></i>
                        </button>
                    </form>

                    <!-- Bell -->
                    <div class="relative" x-data="{ open: false }">
                        @php
                            $notifMenunggu  = \App\Models\Peminjaman::where('status_peminjaman', 'menunggu_konfirmasi')->count();
                            $notifTerlambat = \App\Models\Peminjaman::where('status_peminjaman', 'dipinjam')
                                                ->where('tanggal_kembali_rencana', '<', \Carbon\Carbon::today())->count();
                            $totalNotif = $notifMenunggu + $notifTerlambat;
                            $notifList  = \App\Models\Peminjaman::with(['anggota', 'buku'])
                                ->whereIn('status_peminjaman', ['menunggu_konfirmasi', 'dipinjam'])
                                ->where(function($q) {
                                    $q->where('status_peminjaman', 'menunggu_konfirmasi')
                                      ->orWhereRaw('tanggal_kembali_rencana < CURDATE()');
                                })
                                ->orderBy('created_at', 'desc')
                                ->limit(5)->get();
                        @endphp

                        <button @click="open = !open"
                                class="relative w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition">
                            <i class="fas fa-bell text-base"></i>
                            @if($totalNotif > 0)
                                <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 bg-rose-500 text-white text-[9px] font-bold flex items-center justify-center rounded-full border-2 border-[#0b1120] animate-pulse">
                                    {{ $totalNotif > 9 ? '9+' : $totalNotif }}
                                </span>
                            @endif
                        </button>

                        <div x-show="open"
                             @click.outside="open = false"
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-[#1a2744] border border-white/8 rounded-2xl shadow-2xl z-50 overflow-hidden">

                            <div class="px-4 py-3 border-b border-white/5 flex items-center justify-between bg-white/2">
                                <h4 class="text-sm font-bold text-white">Notifikasi</h4>
                                @if($totalNotif > 0)
                                    <span class="px-2 py-0.5 bg-rose-500/20 text-rose-400 text-xs font-bold rounded-full border border-rose-500/20">{{ $totalNotif }} baru</span>
                                @endif
                            </div>

                            <div class="max-h-72 overflow-y-auto scrollbar-dark">
                                @forelse($notifList as $notif)
                                    <a href="{{ $notif->status_peminjaman == 'menunggu_konfirmasi'
                                        ? route('petugas.peminjaman.index', ['status' => 'menunggu_konfirmasi'])
                                        : route('petugas.pengembalian.index') }}"
                                       class="flex items-start gap-3 px-4 py-3 hover:bg-white/5 transition border-b border-white/3 last:border-0 group">
                                        <div class="mt-1 w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0
                                                    {{ $notif->status_peminjaman == 'menunggu_konfirmasi' ? 'bg-blue-500/20' : 'bg-rose-500/20' }}">
                                            <i class="text-xs {{ $notif->status_peminjaman == 'menunggu_konfirmasi' ? 'fas fa-hourglass-half text-blue-400' : 'fas fa-clock text-rose-400' }}"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-slate-200 truncate">
                                                @if($notif->status_peminjaman == 'menunggu_konfirmasi')
                                                    Pengajuan: {{ $notif->anggota->nama ?? 'Anggota' }}
                                                @else
                                                    Terlambat: {{ Str::limit($notif->buku->judul ?? 'Buku', 30) }}
                                                @endif
                                            </p>
                                            <p class="text-[10px] text-slate-500 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-10 text-center">
                                        <div class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-check text-slate-500"></i>
                                        </div>
                                        <p class="text-sm text-slate-500">Semua beres!</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="px-4 py-2.5 border-t border-white/5 text-center">
                                <a href="{{ route('kepala.laporan') }}" class="text-xs text-blue-400 hover:text-blue-300 font-semibold transition">
                                    Lihat Laporan Lengkap →
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="hidden lg:block w-px h-6 bg-white/10"></div>

                    <div class="hidden lg:block text-right">
                        <div class="text-xs font-semibold text-white" id="current-date">—</div>
                        <div class="text-[10px] text-slate-500 tabular-nums" id="current-time">—</div>
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-y-auto bg-[#0b1120] scrollbar-dark">
                <div class="p-4 lg:p-6 xl:p-8">
                    <div class="max-w-7xl mx-auto">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        (function () {
            function updateTime() {
                const now = new Date();
                const el_d = document.getElementById('current-date');
                const el_t = document.getElementById('current-time');
                if (el_d) el_d.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                if (el_t) el_t.textContent = now.toLocaleTimeString('id-ID');
            }
            updateTime();
            setInterval(updateTime, 1000);
        })();
    </script>

    @stack('scripts')
</body>
</html>
