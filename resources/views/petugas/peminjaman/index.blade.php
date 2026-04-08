@extends('layouts.petugas')

@section('title', 'Transaksi Peminjaman')
@section('page-title', 'Peminjaman Buku')

@section('content')
<div class="space-y-6">

    <!-- 1. NOTIFIKASI -->
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl flex items-center gap-3 shadow-lg animate-fade-in-down">
            <i class="fas fa-check-circle text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto hover:text-white transition"><i class="fas fa-times"></i></button>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-6 py-4 rounded-xl flex items-start gap-3">
            <i class="fas fa-exclamation-circle text-xl mt-0.5"></i>
            <div>
                <h4 class="font-bold mb-1">Terjadi Kesalahan</h4>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-auto hover:text-white transition"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <!-- 2. STATISTIK TRANSAKSI -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Transaksi -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-xl flex items-center justify-between group hover:border-indigo-500/30 transition">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Total Transaksi</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalTransaksi ?? 0) }}</h3>
            </div>
            <div class="w-14 h-14 bg-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400 group-hover:scale-110 transition">
                <i class="fas fa-receipt text-2xl"></i>
            </div>
        </div>

        <!-- Sedang Dipinjam -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-xl flex items-center justify-between group hover:border-amber-500/30 transition">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Sedang Dipinjam</p>
                <h3 class="text-3xl font-bold text-amber-400">{{ number_format($sedangDipinjam ?? 0) }}</h3>
            </div>
            <div class="w-14 h-14 bg-amber-500/20 rounded-xl flex items-center justify-center text-amber-400 group-hover:scale-110 transition">
                <i class="fas fa-book-reader text-2xl"></i>
            </div>
        </div>

        <!-- Terlambat -->
        <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-xl flex items-center justify-between group hover:border-red-500/30 transition">
            <div>
                <p class="text-slate-400 text-sm font-medium mb-1">Terlambat Kembali</p>
                <h3 class="text-3xl font-bold text-red-400">{{ number_format($terlambat ?? 0) }}</h3>
            </div>
            <div class="w-14 h-14 bg-red-500/20 rounded-xl flex items-center justify-center text-red-400 group-hover:scale-110 transition">
                <i class="fas fa-clock text-2xl"></i>
            </div>
        </div>

        <!-- Tombol Cepat -->
        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-6 shadow-xl flex flex-col justify-center items-center text-center group hover:shadow-emerald-500/30 transition cursor-pointer" onclick="window.location='{{ route('petugas.peminjaman.create') }}'">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center text-white mb-2 group-hover:scale-110 transition">
                <i class="fas fa-plus text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-white">Peminjaman Baru</h3>
            <p class="text-xs text-emerald-100">Proses transaksi cepat</p>
        </div>
    </div>

    <!-- 3. TABEL DATA PEMINJAMAN -->
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl overflow-hidden">
        
        <!-- Header & Filter -->
        <div class="p-6 border-b border-slate-700/50">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-white">Daftar Transaksi</h3>
                    <p class="text-sm text-slate-400">Riwayat peminjaman dan pengembalian buku</p>
                </div>
            </div>

            <!-- Form Filter -->
            <form action="{{ route('petugas.peminjaman.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Cari Anggota -->
                <div class="relative md:col-span-1">
                    <input type="text" name="search_anggota" value="{{ request('search_anggota') }}" placeholder="Cari Anggota / NIS..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-600 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                    <i class="fas fa-user absolute left-3.5 top-3 text-slate-500 text-sm"></i>
                </div>

                <!-- Cari Buku -->
                <div class="relative md:col-span-1">
                    <input type="text" name="search_buku" value="{{ request('search_buku') }}" placeholder="Cari Judul Buku..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-800 border border-slate-600 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                    <i class="fas fa-book absolute left-3.5 top-3 text-slate-500 text-sm"></i>
                </div>

                <!-- Filter Status -->
                <select name="status" class="w-full md:col-span-1 px-4 py-2.5 bg-slate-800 border border-slate-600 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    <!-- TAMBAHAN: Opsi Menunggu Konfirmasi -->
                    <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="hilang" {{ request('status') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                </select>

                <!-- Buttons -->
                <div class="flex gap-2 md:col-span-1">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 transition">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <a href="{{ route('petugas.peminjaman.index') }}" class="px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm font-semibold rounded-xl border border-slate-600 transition">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50 text-xs uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4 font-semibold">ID & Tanggal</th>
                        <th class="px-6 py-4 font-semibold">Anggota</th>
                        <th class="px-6 py-4 font-semibold">Buku</th>
                        <th class="px-6 py-4 font-semibold text-center">Jatuh Tempo</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50 text-sm">
                    @forelse($peminjamans as $peminjaman)
                    @php
                        // Cek keterlambatan real-time
                        $isLate = false;
                        if ($peminjaman->status_peminjaman === 'dipinjam' && $peminjaman->tanggal_kembali_rencana < \Carbon\Carbon::today()) {
                            $isLate = true;
                        }
                    @endphp
                    <tr class="hover:bg-slate-800/30 transition group {{ $isLate ? 'bg-red-500/5' : '' }}">
                        
                        <!-- ID & Tanggal -->
                        <td class="px-6 py-4">
                            <div class="font-mono text-xs text-indigo-400 font-bold mb-1">#PMJ-{{ str_pad($peminjaman->id_peminjaman, 5, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-xs text-slate-400">
                                <i class="far fa-calendar-alt mr-1"></i> {{ $peminjaman->tanggal_pinjam->format('d M Y') }}
                            </div>
                            @if($peminjaman->petugas)
                                <div class="text-[10px] text-slate-500 mt-1">Petugas: {{ $peminjaman->petugas->name }}</div>
                            @endif
                        </td>

                        <!-- Anggota -->
                        <td class="px-6 py-4">
                            @if($peminjaman->anggota)
                                <div class="font-bold text-white">{{ $peminjaman->anggota->nama }}</div>
                                <div class="text-xs text-slate-500">{{ $peminjaman->anggota->kelas }}</div>
                                <div class="text-[10px] text-slate-600 font-mono mt-0.5">NIS: {{ $peminjaman->anggota->nis_nisn }}</div>
                            @else
                                <span class="text-red-400 text-xs">Data Anggota Dihapus</span>
                            @endif
                        </td>

                        <!-- Buku -->
                        <td class="px-6 py-4">
                            @if($peminjaman->buku)
                                <div class="font-medium text-slate-200 line-clamp-1">{{ $peminjaman->buku->judul }}</div>
                                <div class="text-xs text-slate-500">{{ $peminjaman->buku->pengarang }}</div>
                            @else
                                <span class="text-red-400 text-xs">Buku Dihapus</span>
                            @endif
                        </td>

                        <!-- Jatuh Tempo -->
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm font-medium {{ $isLate ? 'text-red-400 font-bold' : 'text-slate-300' }}">
                                {{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}
                            </div>
                            @if($isLate)
                                <span class="inline-block mt-1 px-2 py-0.5 bg-red-500/20 text-red-400 text-[10px] rounded font-bold animate-pulse">
                                    TERLAMBAT
                                </span>
                            @elseif($peminjaman->status_peminjaman === 'dipinjam')
                                <div class="text-[10px] text-slate-500 mt-1">
                                    {{ $peminjaman->tanggal_kembali_rencana->diffInDays(\Carbon\Carbon::today(), false) }} hari lagi
                                </div>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td class="px-6 py-4 text-center">
                            @php
                                $badgeClass = '';
                                $badgeIcon = '';
                                $label = $peminjaman->status_peminjaman;
                                
                                if ($isLate) {
                                    $badgeClass = 'bg-red-500/10 text-red-400 border-red-500/20';
                                    $badgeIcon = 'fa-exclamation-circle';
                                    $label = 'Terlambat';
                                } else {
                                    switch($peminjaman->status_peminjaman) {
                                        case 'dipinjam':
                                            $badgeClass = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
                                            $badgeIcon = 'fa-clock';
                                            break;
                                        case 'dikembalikan':
                                            $badgeClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                                            $badgeIcon = 'fa-check-circle';
                                            break;
                                        case 'menunggu_konfirmasi':
                                            $badgeClass = 'bg-blue-500/10 text-blue-400 border-blue-500/20';
                                            $badgeIcon = 'fa-hourglass-half';
                                            break;
                                        case 'hilang':
                                            $badgeClass = 'bg-slate-500/10 text-slate-400 border-slate-500/20';
                                            $badgeIcon = 'fa-question-circle';
                                            break;
                                    }
                                }
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }} border">
                                <i class="fas {{ $badgeIcon }} text-[8px] mr-1.5"></i> {{ ucfirst(str_replace('_', ' ', $label)) }}
                            </span>
                        </td>

                        <!-- Aksi (PERBAIKAN UTAMA DI SINI) -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                
                                {{-- LOGIKA BARU UNTUK STATUS MENUNGGU KONFIRMASI --}}
                                @if($peminjaman->status_peminjaman === 'menunggu_konfirmasi')
                                    
                                    <!-- TOMBOL 1: SETUJUI PEMINJAMAN (ONLINE) -->
                                    <form action="{{ route('petugas.peminjaman.setujui', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('SETUJUI peminjaman ini? Stok buku akan otomatis BERKURANG.');">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 text-emerald-400 hover:text-white flex items-center justify-center transition" title="Terima & Serahkan Buku">
                                            <i class="fas fa-check text-xs"></i> <!-- Ikon Centang -->
                                        </button>
                                    </form>

                                    <!-- TOMBOL 2: TOLAK/BATALKAN PENGAJUAN -->
                                    <form action="{{ route('petugas.peminjaman.destroy', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan peminjaman ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white flex items-center justify-center transition" title="Tolak Pengajuan">
                                            <i class="fas fa-times text-xs"></i> <!-- Ikon Silang -->
                                        </button>
                                    </form>

                                @elseif($peminjaman->status_peminjaman !== 'dikembalikan' && $peminjaman->status_peminjaman !== 'hilang')
                                    
                                    {{-- LOGIKA LAMA UNTUK STATUS DIPINJAM/TERLAMBAT --}}
                                    <!-- Tombol Kembalikan (Hanya jika belum kembali) -->
                                    <form action="{{ route('petugas.peminjaman.kembali', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku ini? Stok akan otomatis bertambah.');">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500 text-blue-400 hover:text-white flex items-center justify-center transition" title="Kembalikan Buku">
                                            <i class="fas fa-undo text-xs"></i> <!-- Ikon Putar/Undo -->
                                        </button>
                                    </form>

                                    <!-- Tombol Hapus/Batal (Jika masih dipinjam) -->
                                    @if($peminjaman->status_peminjaman === 'dipinjam')
                                        <form action="{{ route('petugas.peminjaman.destroy', $peminjaman->id_peminjaman) }}" method="POST" onsubmit="return confirm('Batalkan peminjaman ini? Stok buku akan dikembalikan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white flex items-center justify-center transition" title="Batalkan">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif

                                <!-- Tombol Detail (Mata) - Selalu Muncul -->
                                <button onclick="alert('Fitur detail bisa dikembangkan nanti')" class="w-8 h-8 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-400 hover:text-white flex items-center justify-center transition" title="Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-500">
                                <i class="fas fa-box-open text-5xl mb-4 opacity-30"></i>
                                <p class="text-lg font-medium">Belum ada transaksi peminjaman.</p>
                                <a href="{{ route('petugas.peminjaman.create') }}" class="mt-3 text-indigo-400 hover:text-indigo-300 text-sm font-semibold underline">Buat peminjaman baru sekarang</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($peminjamans->hasPages())
        <div class="p-6 border-t border-slate-700/50 flex items-center justify-between">
            <span class="text-sm text-slate-400">
                Menampilkan <strong class="text-white">{{ $peminjamans->firstItem() }}</strong>-<strong class="text-white">{{ $peminjamans->lastItem() }}</strong> dari <strong class="text-white">{{ $peminjamans->total() }}</strong> transaksi
            </span>
            
            <div class="flex gap-2">
                {{-- Previous --}}
                @if ($peminjamans->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800/50 text-slate-600 border border-slate-700 cursor-not-allowed">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </span>
                @else
                    <a href="{{ $peminjamans->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition border border-slate-700">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </a>
                @endif

                {{-- Numbers --}}
                @foreach ($peminjamans->links()->elements[0] ?? [] as $page => $url)
                    @if ($page == $peminjamans->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-600 text-white font-semibold shadow-lg shadow-indigo-500/30 border border-indigo-500">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition border border-slate-700">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($peminjamans->hasMorePages())
                    <a href="{{ $peminjamans->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition border border-slate-700">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800/50 text-slate-600 border border-slate-700 cursor-not-allowed">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection