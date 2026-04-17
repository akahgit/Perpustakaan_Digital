@extends('layouts.app')

@section('title', $buku->judul . ' — Detail Buku')

@section('content')
@php
    $stokTersedia = (int) ($buku->stok_tersedia ?? 0);
    $stokRusak = (int) ($buku->stok_rusak ?? 0);
    $stokHilang = (int) ($buku->stok_hilang ?? 0);
    $stokAktif = (int) ($buku->stok ?? 0);
    $stokTotalAwal = $stokAktif + $stokHilang;
    $stokDipinjam = max(0, $stokAktif - $stokTersedia - $stokRusak);
    $isAvailable = $stokTersedia > 0;
@endphp
<div class="bg-[#050505] min-h-screen pb-20 pt-10 px-4 sm:px-6 lg:px-8">
    
    <!-- Header / Breadcrumb -->
    <div class="max-w-[1400px] mx-auto mb-10">
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">
            <a href="{{ route('katalog') }}" class="hover:text-indigo-400 transition-colors">Katalog</a>
            <i class="fas fa-chevron-right text-[8px] opacity-30"></i>
            <span class="text-gray-300 truncate">{{ $buku->judul }}</span>
        </nav>
    </div>

    <div class="max-w-[1400px] mx-auto">
        <div class="grid lg:grid-cols-12 gap-12 items-start">
            
            <!-- LEFT: COVER & ACTION -->
            <div class="lg:col-span-5 xl:col-span-4 sticky top-28">
                <div class="relative group">
                    <!-- Dynamic Aura Background -->
                    <div class="absolute -inset-4 bg-gradient-to-tr from-indigo-600/20 via-purple-600/20 to-pink-600/20 rounded-[40px] blur-2xl group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <!-- Cover Container -->
                    <div class="relative bg-[#111] rounded-[32px] border border-white/10 overflow-hidden shadow-2xl overflow-hidden aspect-[3/4]">
                        @if($buku->cover_buku)
                            <img src="{{ Storage::url($buku->cover_buku) }}" alt="{{ $buku->judul }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-[#1e293b] to-[#0f172a] flex flex-col items-center justify-center p-12 text-center">
                                <i class="fas fa-book-sparkles text-7xl text-white/10 mb-6"></i>
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">Digital Archive</span>
                            </div>
                        @endif

                        <!-- Availability Badge -->
                        <div class="absolute top-6 left-6 z-10 px-4 py-2 rounded-full backdrop-blur-xl border {{ $isAvailable ? 'bg-emerald-500/20 border-emerald-500/30 text-emerald-400' : 'bg-rose-500/20 border-rose-500/30 text-rose-400' }} text-[10px] font-black uppercase tracking-widest">
                            {{ $isAvailable ? 'Koleksi Tersedia' : 'Tidak Tersedia' }}
                        </div>
                    </div>
                </div>

                <!-- CTA Sidebar -->
                <div class="mt-8 space-y-4">
                    @auth
                        @if($isAvailable)
                            <button onclick="window.dispatchEvent(new CustomEvent('open-pinjam-modal', {detail: {id: {{ $buku->id_buku }}, judul: '{{ addslashes($buku->judul) }}'}}))"
                                    class="w-full py-5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-black rounded-2xl shadow-2xl shadow-indigo-600/20 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                                <i class="fas fa-bolt"></i>
                                AJUKAN PINJAM SEKARANG
                            </button>
                        @else
                            <button disabled class="w-full py-5 bg-white/5 text-gray-500 font-bold rounded-2xl border border-white/5 cursor-not-allowed">
                                STOK TIDAK TERSEDIA
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full py-5 bg-white/5 hover:bg-white/10 text-white font-bold rounded-2xl border border-white/10 text-center transition-all">
                            LOGIN UNTUK MEMINJAM
                        </a>
                    @endauth

                    <div class="flex items-center gap-4 pt-4">
                        <div class="flex-1 p-4 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                            <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Tersedia</p>
                            <p class="text-xl font-black text-white">{{ $stokTersedia }}</p>
                        </div>
                        <div class="flex-1 p-4 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                            <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Total Aktif</p>
                            <p class="text-xl font-black text-white">{{ $stokAktif }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                            <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Dipinjam</p>
                            <p class="text-lg font-black text-amber-400">{{ $stokDipinjam }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                            <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Rusak</p>
                            <p class="text-lg font-black text-rose-300">{{ $stokRusak }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                            <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Hilang</p>
                            <p class="text-lg font-black text-rose-400">{{ $stokHilang }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 text-center">
                            <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Total Awal</p>
                            <p class="text-lg font-black text-white">{{ $stokTotalAwal }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: CONTENT -->
            <div class="lg:col-span-7 xl:col-span-8 space-y-12">
                
                <!-- Main Header -->
                <div class="animate-fade-in">
                    <div class="mb-4 inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-indigo-500/10 border border-indigo-500/20">
                        <i class="fas fa-bookmark text-indigo-400 text-[10px]"></i>
                        <span class="text-indigo-300 text-[10px] font-black uppercase tracking-widest">{{ $buku->kategori->nama_kategori ?? 'Umum' }}</span>
                    </div>
                    <h1 class="text-4xl lg:text-7xl font-black text-white tracking-tighter leading-tight mb-4">
                        {{ $buku->judul }}
                    </h1>
                    <div class="flex items-center gap-3 text-lg lg:text-2xl text-gray-400 font-medium italic">
                        <span>Oleh:</span>
                        <span class="text-gray-200">{{ $buku->pengarang }}</span>
                    </div>
                </div>

                <!-- Specs Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 py-10 border-y border-white/5">
                    <div class="space-y-1">
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-[0.2em]">Penerbit</p>
                        <p class="text-white font-bold">{{ $buku->penerbit }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-[0.2em]">Terbit</p>
                        <p class="text-white font-bold">{{ $buku->tahun_terbit }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-[0.2em]">Format</p>
                        <p class="text-white font-bold">Hardcopy</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-[0.2em]">ISBN</p>
                        <p class="text-white font-bold font-mono">{{ $buku->isbn ?? '-' }}</p>
                    </div>
                </div>

                <!-- Sinopsis -->
                <div class="prose prose-invert max-w-none">
                    <h3 class="text-xl font-black text-white mb-6 flex items-center gap-3">
                        <span class="w-8 h-px bg-indigo-500"></span>
                        Sinopsis Buku
                    </h3>
                    <div class="text-gray-400 text-lg leading-relaxed space-y-6 text-justify">
                        @if($buku->sinopsis)
                            {!! nl2br(e($buku->sinopsis)) !!}
                        @else
                            <p class="italic opacity-50">Koleksi ini belum memiliki deskripsi sinopsis resmi dalam arsip digital kami.</p>
                        @endif
                    </div>
                </div>

                <!-- Benefits Cards -->
                <div class="grid sm:grid-cols-2 gap-6 pb-12 border-b border-white/5">
                    <div class="p-8 rounded-[32px] bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-all">
                        <div class="w-12 h-12 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-400 mb-6 font-bold text-xl">01</div>
                        <h4 class="text-white font-bold mb-2 text-lg">Pinjam Online</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">Cukup ajukan melalui portal ini, tunggu konfirmasi, dan ambil buku fisiknya di perpustakaan.</p>
                    </div>
                    <div class="p-8 rounded-[32px] bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-all">
                        <div class="w-12 h-12 bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-400 mb-6 font-bold text-xl">02</div>
                        <h4 class="text-white font-bold mb-2 text-lg">Durasi Fleksibel</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">Peminjaman hingga 7 hari dengan opsi perpanjangan (hubungi petugas) untuk kenyamanan membaca.</p>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="pt-12">
                    <div class="flex items-center justify-between mb-10">
                        <h3 class="text-2xl font-black text-white flex items-center gap-3">
                            <span class="w-8 h-px bg-indigo-500"></span>
                            Ulasan Pembaca
                        </h3>
                        <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-xl border border-white/5">
                            <i class="fas fa-star text-amber-400"></i>
                            <span class="text-white font-black">{{ number_format($buku->averageRating, 1) }}</span>
                            <span class="text-gray-500 text-xs font-bold uppercase tracking-widest">/ 5.0</span>
                        </div>
                    </div>

                    <!-- Review Form -->
                    @auth
                        @if(auth()->user()->role === 'anggota')
                            @php
                                $anggota_ini = auth()->user()->anggota;
                                $userReview = $anggota_ini ? $buku->ulasans->where('id_anggota', $anggota_ini->id)->first() : null;
                            @endphp

                            @if(!$userReview)
                                <div class="mb-12 p-8 rounded-[32px] bg-gradient-to-br from-indigo-600/10 to-transparent border border-white/10">
                                    <h4 class="text-white font-bold mb-6">Tulis Ulasan Anda</h4>
                                    <form action="{{ route('ulasan.store', $buku->id_buku) }}" method="POST" class="space-y-6">
                                        @csrf
                                        <div class="space-y-3">
                                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Berikan Rating</label>
                                            <div x-data="{ rating: 0, hover: 0 }" class="flex items-center gap-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <label class="cursor-pointer">
                                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden" 
                                                               x-model="rating" required>
                                                        <i class="fas fa-star text-2xl transition-all duration-200"
                                                           :class="(hover >= {{ $i }} || rating >= {{ $i }}) ? 'text-amber-400 scale-110' : 'text-gray-700'"
                                                           @mouseenter="hover = {{ $i }}"
                                                           @mouseleave="hover = 0"
                                                           @click="rating = {{ $i }}"></i>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="space-y-3">
                                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Ulasan Anda</label>
                                            <textarea name="ulasan" rows="4" maxlength="1000" required
                                                      class="w-full bg-black/40 border border-white/10 rounded-2xl p-5 text-white text-sm outline-none focus:border-indigo-500 transition-all placeholder:text-gray-700"
                                                      placeholder="Apa yang Anda pikirkan tentang buku ini?"></textarea>
                                        </div>
                                        <button type="submit" class="px-8 py-4 bg-white text-black font-black rounded-2xl hover:bg-indigo-500 hover:text-white transition-all shadow-xl shadow-white/5 active:scale-95">
                                            Kirim Ulasan
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endif
                    @endauth

                    <!-- Review List -->
                    <div class="grid gap-6">
                        @forelse($buku->ulasans as $ulasan)
                            <div class="p-8 rounded-[32px] bg-white/[0.02] border border-white/5 relative group hover:bg-white/[0.04] transition-all">
                                <div class="flex items-start justify-between gap-6">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 font-black text-xl">
                                            {{ strtoupper(substr($ulasan->anggota->nama ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h5 class="text-white font-bold mb-1">{{ $ulasan->anggota->nama ?? 'Guest' }}</h5>
                                            <div class="flex items-center gap-1 mb-4">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star text-[10px] {{ $i <= $ulasan->rating ? 'text-amber-400' : 'text-white/10' }}"></i>
                                                @endfor
                                            </div>
                                            <p class="text-gray-400 text-sm leading-relaxed">{{ $ulasan->ulasan }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right flex flex-col items-end">
                                        <span class="text-[9px] text-gray-600 font-black uppercase tracking-widest whitespace-nowrap">{{ $ulasan->created_at->diffForHumans() }}</span>
                                        @auth
                                            @if(isset($anggota_ini) && $anggota_ini->id === $ulasan->id_anggota)
                                                <form action="{{ route('ulasan.destroy', $ulasan->id_ulasan) }}" method="POST" 
                                                      class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity"
                                                      onsubmit="return confirm('Hapus ulasan ini?')">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-20 text-center bg-white/[0.01] border border-dashed border-white/5 rounded-[40px]">
                                <i class="fas fa-comment-dots text-5xl text-white/5 mb-6"></i>
                                <p class="text-gray-500 text-sm italic font-medium">Belum ada ulasan untuk literatur ini. <br> Jadilah yang pertama memberikan perspektif Anda!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PEMINJAMAN --}}
@auth
    @if(auth()->user()->role === 'anggota')
    <div x-data="{
            open: false,
            id_buku: null,
            judul: '',
            tgl_pinjam: '{{ date('Y-m-d') }}',
            tgl_kembali: '{{ date('Y-m-d', strtotime('+7 days')) }}',
            get maxTglKembali() {
                if (!this.tgl_pinjam) return '';
                const d = new Date(this.tgl_pinjam);
                d.setDate(d.getDate() + 7);
                return d.toISOString().split('T')[0];
            },
            get minTglKembali() {
                if (!this.tgl_pinjam) return '';
                const d = new Date(this.tgl_pinjam);
                d.setDate(d.getDate() + 1);
                return d.toISOString().split('T')[0];
            },
            onTglPinjamChange() {
                // Paksa tgl_kembali ke max (7 hari dari pinjam) saat tanggal pinjam berubah
                this.tgl_kembali = this.maxTglKembali;
            }
         }"
         @open-pinjam-modal.window="open = true; id_buku = $event.detail.id; judul = $event.detail.judul; tgl_pinjam = '{{ date('Y-m-d') }}'; tgl_kembali = maxTglKembali"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        
        <div class="absolute inset-0 bg-black/80 backdrop-blur-md" @click="open = false" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"></div>
        
        <div class="relative z-10 w-full max-w-md bg-[#111] border border-white/10 rounded-[40px] shadow-2xl overflow-hidden animate-scale-in"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8"
             x-transition:enter-end="opacity-100 translate-y-0">
            
            <div class="px-8 py-8">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-bold text-white">Form Pinjam</h3>
                    <button @click="open = false" class="text-gray-500 hover:text-white transition-colors duration-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('peminjaman.ajukan') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="id_buku" :value="id_buku">

                    <div class="p-5 rounded-2xl bg-white/5 border border-white/5">
                        <p class="text-[9px] text-gray-500 uppercase font-black tracking-widest mb-1">Target Literasi</p>
                        <p class="text-sm text-white font-bold" x-text="judul"></p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] text-gray-500 uppercase font-black mb-2 ml-1">Waktu Pinjam</label>
                            <input type="date" name="tanggal_pinjam" x-model="tgl_pinjam" required
                                   @change="onTglPinjamChange()"
                                   :min="'{{ date('Y-m-d') }}'"
                                   class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:border-indigo-500 transition-all outline-none">
                        </div>

                        <div>
                            <label class="block text-[10px] text-gray-500 uppercase font-black mb-2 ml-1">Rencana Kembali</label>
                            <input type="date" name="tanggal_kembali_rencana" required x-model="tgl_kembali"
                                   :min="minTglKembali"
                                   :max="maxTglKembali"
                                   class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:border-indigo-500 transition-all outline-none">
                            <p class="text-[10px] text-gray-500 mt-2 ml-1">Maksimal <span class="text-indigo-400 font-bold">7 hari</span> dari tanggal pinjam.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-indigo-500/5 border border-indigo-500/10">
                        <p class="text-[9px] text-indigo-400 font-bold uppercase tracking-widest leading-loose text-center">
                            Maksimum durasi peminjaman standar adalah 7 hari kalender.
                        </p>
                    </div>

                    <button type="submit" class="w-full py-5 bg-white text-black font-black text-sm rounded-2xl shadow-xl shadow-white/5 hover:bg-indigo-500 hover:text-white transition-all active:scale-95">
                        KONFIRMASI PENGAJUAN
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
@endauth

@endsection
