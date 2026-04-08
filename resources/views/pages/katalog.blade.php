@extends('layouts.app')

@section('title', 'Katalog Buku - Perpustakaan Digital')

@section('content')
<div class="bg-[#050505] min-h-screen pb-20">
    
    <!-- HEADER SECTION -->
    <section class="pt-12 pb-8 border-b border-white/5">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold mb-2">
                        Katalog <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400">Buku</span>
                    </h1>
                    <p class="text-gray-400 text-lg">Jelajahi koleksi buku kami dan temukan bacaan favoritmu.</p>
                </div>
                
                <!-- Stats Dinamis -->
                <div class="hidden md:flex items-center gap-4 text-sm text-gray-400 bg-white/5 px-4 py-2 rounded-full border border-white/10">
                    <span><strong class="text-white">{{ $bukus->total() }}</strong> Buku Ditampilkan</span>
                    <span class="w-px h-4 bg-white/20"></span>
                    <span><strong class="text-green-400">{{ number_format($bukus->sum('stok_tersedia')) }}</strong> Total Stok Tersedia</span>
                </div>
            </div>

            <!-- SEARCH & FILTER BAR -->
            <form action="{{ route('katalog') }}" method="GET" class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-4 md:p-6 shadow-2xl">
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-500"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari judul buku, pengarang, atau penerbit..." 
                               class="w-full pl-12 pr-4 py-3.5 bg-[#050505] border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-purple-500/50 focus:ring-1 focus:ring-purple-500/50 transition">
                    </div>

                    <!-- Filter Genre -->
                    <div class="relative min-w-[200px]">
                        <select name="kategori" onchange="this.form.submit()" class="w-full appearance-none pl-4 pr-10 py-3.5 bg-[#050505] border border-white/10 rounded-xl text-white text-sm focus:outline-none focus:border-purple-500/50 cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </div>
                    </div>

                    <!-- Reset Button -->
                    @if(request('search') || request('kategori'))
                        <a href="{{ route('katalog') }}" class="px-6 py-3.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 rounded-xl text-sm font-medium transition flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </section>

    <!-- CATALOG GRID SECTION -->
    <section class="py-12">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Results Info -->
            <div class="flex justify-between items-center mb-8">
                <p class="text-gray-400 text-sm">Menampilkan <span class="text-white font-medium">{{ $bukus->firstItem() ?? 0 }} - {{ $bukus->lastItem() ?? 0 }}</span> dari <span class="text-white font-medium">{{ $bukus->total() }}</span> buku</p>
            </div>

            <!-- Grid Books -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @forelse($bukus as $buku)
                @php
                    $colors = [
                        'from-purple-500 to-pink-600', 'from-blue-500 to-cyan-600', 'from-indigo-500 to-blue-600',
                        'from-emerald-500 to-teal-600', 'from-orange-500 to-red-600', 'from-amber-500 to-orange-600',
                    ];
                    $colorClass = $colors[$buku->id_buku % count($colors)];
                    $isAvailable = $buku->stok_tersedia > 0;
                @endphp
                <div class="group bg-[#0a0a0a] rounded-2xl border border-white/5 overflow-hidden hover:border-purple-500/30 hover:shadow-xl hover:shadow-purple-500/10 transition duration-300 flex flex-col">
                    
                    <!-- Cover Image Area -->
                    <div class="aspect-[2/3] bg-gradient-to-br {{ $colorClass }} relative overflow-hidden">
                        @if($buku->cover_buku && file_exists(public_path('storage/' . $buku->cover_buku)))
                            <img src="{{ asset('storage/' . $buku->cover_buku) }}" alt="{{ $buku->judul }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-60 transition duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-book text-6xl text-white/20 group-hover:scale-110 transition duration-500"></i>
                            </div>
                        @endif
                        
                        <!-- Stock Badge -->
                        <div class="absolute top-3 left-3 px-2.5 py-1 bg-black/60 backdrop-blur-md rounded-md text-xs font-semibold {{ $isAvailable ? 'text-green-400 border border-green-500/20' : 'text-red-400 border border-red-500/20' }} flex items-center gap-1.5">
                            <i class="fas {{ $isAvailable ? 'fa-check-circle' : 'fa-times-circle' }} text-[10px]"></i>
                            {{ $isAvailable ? 'Tersedia: ' . $buku->stok_tersedia : 'Habis' }}
                        </div>

                        <!-- Hover Overlay Actions -->
                        <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-3 backdrop-blur-[2px]">
                            <button class="w-10 h-10 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center transition transform hover:scale-110" title="Detail">
                                <i class="fas fa-eye text-white text-sm"></i>
                            </button>
                            
                            @if($isAvailable)
                                <!-- TOMBOL PINJAM YANG MENGAKTIFKAN MODAL -->
                                <button type="button" onclick="openPinjamModal({{ $buku->id_buku }}, '{{ addslashes($buku->judul) }}')" 
                                        class="w-10 h-10 bg-purple-600 hover:bg-purple-500 backdrop-blur-sm rounded-full flex items-center justify-center transition transform hover:scale-110 shadow-lg shadow-purple-500/30" title="Ajukan Peminjaman">
                                    <i class="fas fa-hand-holding-heart text-white text-sm"></i>
                                </button>
                            @else
                                <button disabled class="w-10 h-10 bg-gray-600/50 cursor-not-allowed backdrop-blur-sm rounded-full flex items-center justify-center" title="Stok Habis">
                                    <i class="fas fa-lock text-white text-sm"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Book Info -->
                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="font-bold text-white text-base mb-1 line-clamp-1 group-hover:text-purple-400 transition" title="{{ $buku->judul }}">
                            {{ $buku->judul }}
                        </h3>
                        <p class="text-sm text-gray-400 mb-3 line-clamp-1" title="{{ $buku->pengarang }}">
                            {{ $buku->pengarang }}
                        </p>
                        
                        <div class="mt-auto pt-3 border-t border-white/5 flex items-center justify-between">
                            <span class="text-xs text-gray-500 bg-white/5 px-2 py-1 rounded">{{ $buku->tahun_terbit }}</span>
                            @if($buku->kategori)
                                <span class="text-[10px] text-purple-300 bg-purple-500/10 px-2 py-1 rounded border border-purple-500/20">
                                    {{ $buku->kategori->nama_kategori }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/5 mb-4">
                        <i class="fas fa-search text-3xl text-gray-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Buku Tidak Ditemukan</h3>
                    <p class="text-gray-400 max-w-md mx-auto">
                        Maaf, tidak ada buku yang cocok dengan pencarian "{{ request('search') }}" atau kategori yang dipilih.
                    </p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($bukus->hasPages())
            <div class="mt-16 flex justify-center">
                <nav class="flex items-center gap-2 flex-wrap">
                    @if ($bukus->onFirstPage())
                        <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-gray-600 cursor-not-allowed"><i class="fas fa-chevron-left text-sm"></i></span>
                    @else
                        <a href="{{ $bukus->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 hover:text-white transition"><i class="fas fa-chevron-left text-sm"></i></a>
                    @endif

                    @foreach ($bukus->links()->elements[0] ?? [] as $page => $url)
                        @if ($page == $bukus->currentPage())
                            <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-purple-600 border border-purple-500 text-white font-semibold shadow-lg shadow-purple-500/25">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 hover:text-white transition">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($bukus->hasMorePages())
                        <a href="{{ $bukus->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:bg-white/10 hover:text-white transition"><i class="fas fa-chevron-right text-sm"></i></a>
                    @else
                        <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-gray-600 cursor-not-allowed"><i class="fas fa-chevron-right text-sm"></i></span>
                    @endif
                </nav>
            </div>
            @endif
        </div>
    </section>
</div>

<!-- MODAL PENGAJUAN PEMINJAMAN -->
<div id="pinjamModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-[#1e293b] rounded-2xl border border-slate-700 w-full max-w-md p-6 shadow-2xl transform scale-95 transition-transform duration-300" id="modalContent">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-6 border-b border-slate-700 pb-4">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-hand-holding-heart text-purple-400"></i>
                Ajukan Peminjaman
            </h3>
            <button onclick="closePinjamModal()" class="text-slate-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Pesan Sukses/Error -->
        @if(session('success'))
            <div class="mb-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('peminjaman.ajukan') }}" method="POST">
            @csrf
            <input type="hidden" name="id_buku" id="modalIdBuku">
            
            <div class="mb-4">
                <label class="block text-sm text-slate-400 mb-1">Judul Buku</label>
                <div class="text-white font-bold text-lg" id="modalJudulBuku">-</div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-300 mb-2">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" required 
                       class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-purple-500 outline-none transition">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-300 mb-2">Rencana Kembali</label>
                <input type="date" name="tanggal_kembali_rencana" min="{{ date('Y-m-d', strtotime('+7 days')) }}" 
                       value="{{ date('Y-m-d', strtotime('+7 days')) }}" required 
                       class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-purple-500 outline-none transition">
                <p class="text-xs text-slate-500 mt-1">Maksimal 7 hari dari tanggal pinjam.</p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closePinjamModal()" class="flex-1 px-4 py-3 rounded-xl border border-slate-600 text-slate-300 hover:bg-slate-800 transition font-medium">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-3 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-xl shadow-lg shadow-purple-500/30 transition flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i> Ajukan Pinjam
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script Modal -->
<!-- Script Modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('pinjamModal');
    const modalContent = document.getElementById('modalContent');
    const modalIdBuku = document.getElementById('modalIdBuku');
    const modalJudulBuku = document.getElementById('modalJudulBuku');

    // Fungsi Buka Modal
    window.openPinjamModal = function(id, judul) {
        if(modalIdBuku && modalJudulBuku) {
            modalIdBuku.value = id;
            modalJudulBuku.textContent = judul;
        }
        
        if(modal) {
            modal.classList.remove('hidden');
            // Trigger reflow agar animasi jalan
            void modal.offsetWidth; 
            modal.classList.remove('opacity-0');
            if(modalContent) {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }
        }
    };

    // Fungsi Tutup Modal
    window.closePinjamModal = function() {
        if(modal) {
            modal.classList.add('opacity-0');
            if(modalContent) {
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
            }
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    };

    // Close on click outside
    if(modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) window.closePinjamModal();
        });
    }
});
</script>
@endsection