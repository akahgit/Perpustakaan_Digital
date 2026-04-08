@extends('layouts.petugas')

@section('title', 'Data Buku - Petugas')
@section('page-title', 'Data Buku')

@section('content')
    <div class="space-y-6">

        <!-- 1. NOTIFIKASI SUKSES -->
        @if (session('success'))
            <div
                class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl flex items-center gap-3 shadow-lg animate-fade-in-down">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto hover:text-white transition"><i
                        class="fas fa-times"></i></button>
            </div>
        @endif

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

            <!-- Card 1: Total Eksemplar (Bukan Judul) -->
            <div class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-xl flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium mb-1">Total Buku (Fisik)</p>
                    <h3 class="text-3xl font-bold text-white">{{ number_format($totalEksemplar ?? 0) }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ number_format($judulBerbeda ?? 0) }} Judul Berbeda</p>
                </div>
                <div class="w-14 h-14 bg-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400">
                    <i class="fas fa-books text-2xl"></i>
                </div>
            </div>

            <!-- Card 2: Tersedia (Siap Dipinjam) -->
            <div
                class="bg-[#1e293b] rounded-2xl p-6 border border-emerald-500/30 shadow-xl flex items-center justify-between">
                <div>
                    <p class="text-emerald-200 text-sm font-medium mb-1">Tersedia di Rak</p>
                    <h3 class="text-3xl font-bold text-emerald-400">{{ number_format($tersedia ?? 0) }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Siap Dipinjam</p>
                </div>
                <div class="w-14 h-14 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>

            <!-- Card 3: Sedang Dipinjam -->
            <div
                class="bg-[#1e293b] rounded-2xl p-6 border border-amber-500/30 shadow-xl flex items-center justify-between">
                <div>
                    <p class="text-amber-200 text-sm font-medium mb-1">Sedang Dipinjam</p>
                    <h3 class="text-3xl font-bold text-amber-400">{{ number_format($sedangDipinjam ?? 0) }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Dalam Peredaran</p>
                </div>
                <div class="w-14 h-14 bg-amber-500/20 rounded-xl flex items-center justify-center text-amber-400">
                    <i class="fas fa-hand-holding-book text-2xl"></i>
                </div>
            </div>

            <!-- Card 4: Habis / Rusak (Opsional) -->
            <div
                class="bg-[#1e293b] rounded-2xl p-6 border border-slate-700/50 shadow-xl flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm font-medium mb-1">Stok Menipis/Habis</p>
                    <h3 class="text-3xl font-bold text-slate-400">
                        {{ number_format(\App\Models\Buku::where('stok_tersedia', 0)->count()) }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Perlu Restock</p>
                </div>
                <div class="w-14 h-14 bg-slate-500/20 rounded-xl flex items-center justify-center text-slate-400">
                    <i class="fas fa-box-open text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- 3. TABEL DATA BUKU -->
        <div class="bg-[#1e293b] rounded-2xl border border-slate-700/50 shadow-xl overflow-hidden">

            <!-- Header Tabel: Search, Filter, Tombol Tambah -->
            <div
                class="p-6 border-b border-slate-700/50 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="text-center lg:text-left">
                    <h3 class="text-lg font-bold text-white">Daftar Buku</h3>
                    <p class="text-sm text-slate-400">Kelola koleksi buku perpustakaan</p>
                </div>

                <form action="{{ route('petugas.buku.index') }}" method="GET"
                    class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                    <!-- Search Input -->
                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul, pengarang..."
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-800/50 border border-slate-600 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                        <i class="fas fa-search absolute left-3.5 top-3 text-slate-500 text-sm"></i>
                    </div>

                    <!-- Filter Genre -->
                    <select name="genre"
                        class="w-full sm:w-auto px-4 py-2.5 bg-slate-800/50 border border-slate-600 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500 cursor-pointer">
                        <option value="">Semua Genre</option>
                        @foreach ($kategoris as $kat)
                            <option value="{{ $kat->slug }}" {{ request('genre') == $kat->slug ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="submit"
                            class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/30 transition whitespace-nowrap">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('petugas.buku.index') }}"
                            class="px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm font-semibold rounded-xl border border-slate-600 transition whitespace-nowrap">
                            Reset
                        </a>
                        <a href="{{ route('petugas.buku.create') }}"
                            class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/30 transition flex items-center gap-2 whitespace-nowrap">
                            <i class="fas fa-plus"></i>
                            <span class="hidden sm:inline">Tambah Buku</span>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-800/50 text-xs uppercase tracking-wider text-slate-400">
                            <th class="px-6 py-4 font-semibold w-16">No</th>
                            <th class="px-6 py-4 font-semibold">Judul & Cover</th>
                            <th class="px-6 py-4 font-semibold">Pengarang</th>
                            <th class="px-6 py-4 font-semibold">Penerbit</th>
                            <th class="px-6 py-4 font-semibold text-center">Tahun</th>
                            <th class="px-6 py-4 font-semibold text-center">Stok</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50 text-sm">
                        @forelse($bukus as $index => $buku)
                            <tr class="hover:bg-slate-800/30 transition group">
                                <!-- Nomor Urut Pagination -->
                                <td class="px-6 py-4 text-slate-400 font-medium">
                                    {{ $bukus->firstItem() + $index }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Cover Image or Placeholder -->
                                        @if ($buku->cover_buku && file_exists(public_path('storage/' . $buku->cover_buku)))
                                            <img src="{{ asset('storage/' . $buku->cover_buku) }}"
                                                alt="{{ $buku->judul }}"
                                                class="w-10 h-14 object-cover rounded shadow-md bg-slate-700">
                                        @else
                                            <div
                                                class="w-10 h-14 rounded bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg flex-shrink-0">
                                                <i class="fas fa-book text-white/60 text-xs"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <div
                                                class="font-bold text-white group-hover:text-indigo-300 transition line-clamp-1">
                                                {{ $buku->judul }}</div>
                                            <div class="text-xs text-slate-500 mt-0.5">
                                                {{ $buku->kategori->nama_kategori ?? 'Umum' }} • ID:
                                                {{ str_pad($buku->id_buku, 4, '0', STR_PAD_LEFT) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-slate-300">{{ $buku->pengarang }}</td>
                                <td class="px-6 py-4 text-slate-300">{{ $buku->penerbit }}</td>
                                <td class="px-6 py-4 text-center text-slate-400">{{ $buku->tahun_terbit }}</td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        // PENTING: Kita paksa ambil nilai stok_tersedia, jika null anggap 0
                                        $sisa_stok = $buku->stok_tersedia ?? 0;
                                    @endphp

                                    @if ($sisa_stok > 10)
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            {{ $sisa_stok }} eks
                                        </span>
                                    @elseif($sisa_stok > 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            {{ $sisa_stok }} eks
                                        </span>
                                    @else
                                        <!-- Ini yang harusnya muncul untuk buku 'Kebun Binatang' karena stok_tersedia = 0 -->
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/20">
                                            Habis
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('petugas.buku.edit', ['data_buku' => $buku->id_buku]) }}"
                                            class="w-8 h-8 rounded-lg bg-indigo-500/10 hover:bg-indigo-500 text-indigo-400 hover:text-white flex items-center justify-center transition"
                                            title="Edit">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>

                                        <form action="{{ route('petugas.buku.destroy', ['data_buku' => $buku->id_buku]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku \'{{ $buku->judul }}\'? Data tidak dapat dikembalikan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white flex items-center justify-center transition"
                                                title="Hapus">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-500">
                                        <i class="fas fa-book-open text-5xl mb-4 opacity-30"></i>
                                        <p class="text-lg font-medium">Tidak ada data buku ditemukan.</p>
                                        <p class="text-sm">Coba ubah kata kunci pencarian atau filter kategori.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($bukus->hasPages())
                <div class="p-6 border-t border-slate-700/50 flex items-center justify-between">
                    <span class="text-sm text-slate-400">
                        Menampilkan <strong class="text-white">{{ $bukus->firstItem() }}</strong>-<strong
                            class="text-white">{{ $bukus->lastItem() }}</strong> dari <strong
                            class="text-white">{{ $bukus->total() }}</strong> buku
                    </span>

                    <div class="flex gap-2">
                        {{-- Previous Button --}}
                        @if ($bukus->onFirstPage())
                            <span
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800/50 text-slate-600 border border-slate-700 cursor-not-allowed">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </span>
                        @else
                            <a href="{{ $bukus->previousPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition border border-slate-700">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </a>
                        @endif

                        {{-- Simple Page Numbers --}}
                        @foreach ($bukus->links()->elements[0] ?? [] as $page => $url)
                            @if ($page == $bukus->currentPage())
                                <span
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-600 text-white font-semibold shadow-lg shadow-indigo-500/30 border border-indigo-500">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition border border-slate-700">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Button --}}
                        @if ($bukus->hasMorePages())
                            <a href="{{ $bukus->nextPageUrl() }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition border border-slate-700">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        @else
                            <span
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-800/50 text-slate-600 border border-slate-700 cursor-not-allowed">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
