<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Kepala - Perpustakaan Digital')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite + Tailwind CSS v4 -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
    
    @stack('styles')
</head>

<body class="bg-[#0b1120] text-slate-300 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f172a] border-r border-slate-800 transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col">
            
            <!-- Logo -->
            <div class="h-20 flex items-center px-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <i class="fas fa-book-open text-white text-lg"></i>
                    </div>
                    <div>
                        <div class="font-bold text-white leading-tight">Perpustakaan</div>
                        <div class="text-[10px] text-blue-400 font-semibold tracking-wider">KEPALA PERPUSTAKAAN</div>
                    </div>
                </div>
            </div>

            <!-- Menu Navigation -->
            <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
                
                <!-- Group: Menu Utama -->
                <div class="mb-6">
                    <div class="px-3 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Menu Utama</div>
                    
                    <a href="{{ route('kepala.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('kepala.dashboard') ? 'bg-blue-600/10 text-blue-400 border-r-2 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} transition group">
                        <i class="fas fa-th-large w-5 text-center"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('kepala.laporan') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('kepala.laporan*') ? 'bg-blue-600/10 text-blue-400 border-r-2 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} transition group">
                        <i class="fas fa-file-alt w-5 text-center"></i>
                        <span class="font-medium">Laporan</span>
                    </a>

                    <a href="{{ route('kepala.statistik') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('kepala.statistik*') ? 'bg-blue-600/10 text-blue-400 border-r-2 border-blue-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }} transition group">
                        <i class="fas fa-chart-bar w-5 text-center"></i>
                        <span class="font-medium">Statistik</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile (Bottom Sidebar) -->
            <div class="p-4 border-t border-slate-800 bg-[#0f172a]">
                <div class="flex items-center gap-3 p-2 rounded-xl bg-slate-800/50 border border-slate-700/50">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold shadow-md">
                        KP
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-400 truncate">Kepala Perpustakaan</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-white transition p-1" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- TOP BAR (HEADER) -->
            <header class="h-20 bg-[#0b1120] border-b border-slate-800 flex items-center justify-between px-6 lg:px-8 z-40">
                <!-- Left: Title & Mobile Toggle -->
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-400 hover:text-white">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-bold text-white hidden sm:block">@yield('page-title', 'Dashboard')</h1>
                </div>

                <!-- Right: Search, Notif, Date -->
                <div class="flex items-center gap-6">
                    
                    <!-- 1. SEARCH GLOBAL (AKTIF) -->
                    <form action="{{ route('kepala.search') }}" method="GET" class="hidden md:block relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari buku, anggota, atau transaksi..." 
                               class="w-64 pl-10 pr-4 py-2 bg-slate-800/50 border border-slate-700 rounded-lg text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                        <button type="submit" class="absolute left-3 top-2.5 text-slate-500 hover:text-blue-400 transition">
                            <i class="fas fa-search text-sm"></i>
                        </button>
                    </form>

                    <!-- 2. NOTIFIKASI LONCENG (DINAMIS + DROPDOWN) -->
                    <div class="relative" x-data="{ open: false }">
                        @php
                            // Hitung Notifikasi Real-time (Sama seperti petugas)
                            $notifMenunggu = \App\Models\Peminjaman::where('status_peminjaman', 'menunggu_konfirmasi')->count();
                            $notifTerlambat = \App\Models\Peminjaman::where('status_peminjaman', 'dipinjam')
                                                ->where('tanggal_kembali_rencana', '<', \Carbon\Carbon::today())->count();
                            $totalNotif = $notifMenunggu + $notifTerlambat;
                            
                            // Ambil 5 notifikasi terbaru
                            $notifList = \App\Models\Peminjaman::with(['anggota', 'buku'])
                                ->whereIn('status_peminjaman', ['menunggu_konfirmasi', 'dipinjam'])
                                ->where(function($q) {
                                    $q->where('status_peminjaman', 'menunggu_konfirmasi')
                                      ->orWhereRaw('tanggal_kembali_rencana < CURDATE()');
                                })
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp

                        <!-- Tombol Lonceng -->
                        <button @click="open = !open" @click.away="open = false" class="relative text-slate-400 hover:text-white transition focus:outline-none">
                            <i class="fas fa-bell text-lg"></i>
                            @if($totalNotif > 0)
                                <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-[#0b1120] animate-pulse">
                                    {{ $totalNotif }}
                                </span>
                            @endif
                        </button>

                        <!-- Dropdown Menu Notifikasi -->
                        <div x-show="open" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="absolute right-0 mt-3 w-80 bg-[#1e293b] border border-slate-700 rounded-xl shadow-2xl z-50 overflow-hidden">
                            
                            <!-- Header Dropdown -->
                            <div class="px-4 py-3 border-b border-slate-700 bg-slate-800/50 flex justify-between items-center">
                                <h4 class="text-sm font-bold text-white">Notifikasi</h4>
                                <span class="text-xs text-blue-400 font-semibold">{{ $totalNotif }} Baru</span>
                            </div>

                            <!-- List Notifikasi -->
                            <div class="max-h-64 overflow-y-auto">
                                @forelse($notifList as $notif)
                                    <a href="{{ $notif->status_peminjaman == 'menunggu_konfirmasi' ? route('petugas.peminjaman.index', ['status' => 'menunggu_konfirmasi']) : route('petugas.pengembalian.index') }}" 
                                       class="block px-4 py-3 hover:bg-slate-800 transition border-b border-slate-700/50 last:border-0 group">
                                        <div class="flex items-start gap-3">
                                            <!-- Icon Status -->
                                            <div class="mt-1 w-2 h-2 rounded-full flex-shrink-0 {{ $notif->status_peminjaman == 'menunggu_konfirmasi' ? 'bg-blue-500' : 'bg-red-500' }}"></div>
                                            
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-slate-200 font-medium truncate group-hover:text-white">
                                                    @if($notif->status_peminjaman == 'menunggu_konfirmasi')
                                                        Pengajuan Baru: {{ $notif->anggota->nama ?? 'Anggota' }}
                                                    @else
                                                        Terlambat: {{ $notif->buku->judul ?? 'Buku' }}
                                                    @endif
                                                </p>
                                                <p class="text-xs text-slate-500 truncate mt-0.5">
                                                    {{ $notif->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-8 text-center text-slate-500 text-sm">
                                        <i class="fas fa-check-circle text-2xl mb-2 opacity-50"></i>
                                        <p>Tidak ada notifikasi baru.</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Footer Dropdown -->
                            <div class="px-4 py-2 bg-slate-800/50 border-t border-slate-700 text-center">
                                <a href="{{ route('petugas.peminjaman.index') }}" class="text-xs text-blue-400 hover:text-blue-300 font-medium">Lihat Semua Aktivitas &rarr;</a>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Date Time -->
                    <div class="hidden lg:flex items-center gap-2 text-right border-l border-slate-700 pl-6">
                        <i class="far fa-calendar-alt text-blue-400"></i>
                        <div>
                            <div class="text-sm font-semibold text-white" id="current-date">Sabtu, 21 Februari 2026</div>
                            <div class="text-xs text-slate-400" id="current-time">11:37:39</div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT SCROLLABLE -->
            <main class="flex-1 overflow-y-auto bg-[#0b1120] p-6 lg:p-8 scroll-smooth">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Script Jam Realtime -->
    <script>
        function updateTime() {
            const now = new Date();
            const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', optionsDate);
            document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>

    @stack('scripts')
</body>
</html>