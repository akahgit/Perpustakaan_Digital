@extends('layouts.app')

@section('title', 'Peminjaman Saya - Perpustakaan Digital')

@section('content')
<div class="bg-[#050505] min-h-screen pb-20" x-data="riwayatPayment()">
    
    <!-- HEADER SECTION -->
    <section class="pt-12 pb-8 border-b border-white/5">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl lg:text-5xl font-bold mb-2">
                Peminjaman <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400">Saya</span>
            </h1>
            <p class="text-gray-400 text-lg">Kelola buku yang sedang Anda pinjam dan proses pengembalian.</p>
        </div>
    </section>

    <!-- STATS CARDS (DINAMIS) -->
    <section class="py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card 1: Sedang Dipinjam -->
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 flex items-center gap-4 hover:border-purple-500/30 transition group">
                    <div class="w-14 h-14 bg-purple-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-book-open text-2xl text-purple-400"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">{{ $peminjamans->whereIn('status_peminjaman', ['dipinjam', 'terlambat'])->count() }}</div>
                        <div class="text-sm text-gray-400">Sedang Aktif</div>
                    </div>
                </div>

                <!-- Card 2: Terlambat -->
                @php
                    $jumlahTerlambat = $peminjamans->filter(function($p) {
                        return $p->tanggal_kembali_rencana < \Carbon\Carbon::today() && $p->status_peminjaman == 'dipinjam';
                    })->count();
                @endphp
                <div class="bg-[#0a0a0a] border {{ $jumlahTerlambat > 0 ? 'border-red-500/40' : 'border-white/10' }} rounded-2xl p-6 flex items-center gap-4 hover:border-red-500/40 transition group">
                    <div class="w-14 h-14 {{ $jumlahTerlambat > 0 ? 'bg-red-500/20' : 'bg-red-500/10' }} rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-exclamation-triangle text-2xl {{ $jumlahTerlambat > 0 ? 'text-red-400' : 'text-red-400/50' }}"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold {{ $jumlahTerlambat > 0 ? 'text-red-400' : 'text-white' }} mb-1">{{ $jumlahTerlambat }}</div>
                        <div class="text-sm text-gray-400">Terlambat</div>
                    </div>
                </div>

                <!-- Card 3: Total Denda -->
                <div class="bg-[#0a0a0a] border border-amber-500/20 rounded-2xl p-6 flex items-center gap-4 hover:border-amber-500/40 transition group">
                    <div class="w-14 h-14 bg-amber-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-coins text-2xl text-amber-400"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">Rp {{ number_format($totalDenda ?? 0, 0, ',', '.') }}</div>
                        <div class="text-sm text-gray-400">Total Denda</div>
                    </div>
                </div>

                <!-- Card 4: Total Dikembalikan -->
                @php
                    $totalKembali = \App\Models\Peminjaman::where('id_anggota', $anggota->id)->where('status_peminjaman', 'dikembalikan')->count();
                @endphp
                <div class="bg-[#0a0a0a] border border-emerald-500/20 rounded-2xl p-6 flex items-center gap-4 hover:border-emerald-500/40 transition group">
                    <div class="w-14 h-14 bg-emerald-500/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-check-circle text-2xl text-emerald-400"></i>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">{{ $totalKembali }}</div>
                        <div class="text-sm text-gray-400">Total Dikembalikan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WARNING ALERT (PREMIUM REDESIGN) -->
    @if($jumlahTerlambat > 0)
    <section class="py-4">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative group overflow-hidden rounded-[32px] p-0.5 border border-red-500/30 bg-red-500/5 transition-all hover:border-red-500/50 hover:shadow-2xl hover:shadow-red-500/10">
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-red-500/50 to-transparent"></div>
                <div class="relative bg-[#050505]/60 backdrop-blur-3xl rounded-[30px] p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-red-500/10 rounded-2xl flex items-center justify-center relative flex-shrink-0 border border-red-500/20 shadow-inner">
                            <i class="fas fa-triangle-exclamation text-2xl text-red-500"></i>
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full animate-ping opacity-75"></span>
                        </div>
                        <div>
                            <h4 class="text-xl font-black text-white tracking-tight mb-1">Akses Literasi Terhambat!</h4>
                            <p class="text-gray-400 text-sm max-w-xl">
                                Anda memiliki <span class="text-red-400 font-bold italic">{{ $jumlahTerlambat }} peminjaman kadaluarsa</span>. Mohon segera lakukan pengembalian buku untuk memulihkan hak peminjaman penuh Anda dan menghindari denda berkelanjutan.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('riwayat') }}" class="group/btn relative px-8 py-3 bg-red-600 text-white font-black rounded-xl text-xs tracking-widest transition-all hover:scale-105 active:scale-95 shadow-xl shadow-red-600/20 flex items-center gap-3">
                        LIHAT RIWAYAT DENDA
                        <i class="fas fa-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- LIST BUKU DIPINJAM -->
    <section class="py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                <i class="fas fa-list-ul text-purple-400"></i>
                Buku yang Sedang Dipinjam
            </h2>

            <div class="space-y-4">
                @forelse($peminjamans as $p)
                    @php
                        $isLate = $p->tanggal_kembali_rencana < \Carbon\Carbon::today() && $p->status_peminjaman == 'dipinjam';
                        $isDitolak = $p->status_peminjaman == 'ditolak';
                        $daysLate = $isLate ? $p->tanggal_kembali_rencana->diffInDays(\Carbon\Carbon::today(), false) : 0;
                        $dendaSaatIni = $daysLate * 1000; 
                        
                        $borderColor = ($isLate || $isDitolak) ? 'border-red-500/30' : 'border-white/10';
                        $badgeClass = ($isLate || $isDitolak) ? 'bg-red-500/10 text-red-400 border-red-500/20' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                        $statusText = $isDitolak ? 'Ditolak' : ($isLate ? 'Terlambat' : ($p->status_peminjaman == 'menunggu_konfirmasi' ? 'Menunggu Konfirmasi' : 'Aktif'));
                        
                        $colors = ['from-purple-500 to-pink-600', 'from-blue-500 to-cyan-600', 'from-indigo-500 to-blue-600', 'from-emerald-500 to-teal-600'];
                        $colorClass = $colors[$p->buku->id_buku % count($colors)];
                    @endphp

                    <div class="bg-[#0a0a0a] border {{ $borderColor }} rounded-2xl p-6 flex flex-col md:flex-row items-center gap-6 hover:border-purple-500/30 transition">
                        
                        <!-- Cover Mini -->
                        <div class="w-20 h-28 bg-gradient-to-br {{ $colorClass }} rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg relative overflow-hidden">
                            @if($p->buku->cover_buku && file_exists(public_path('storage/' . $p->buku->cover_buku)))
                                <img src="{{ asset('storage/' . $p->buku->cover_buku) }}" class="w-full h-full object-cover opacity-80">
                            @else
                                <i class="fas fa-book text-3xl text-white/40"></i>
                            @endif
                            
                            @if($p->status_peminjaman == 'menunggu_konfirmasi')
                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center backdrop-blur-[1px]">
                                    <i class="fas fa-clock text-2xl text-amber-400 animate-pulse"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Info Buku -->
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-xl font-bold text-white mb-1">{{ $p->buku->judul ?? 'Buku Dihapus' }}</h3>
                            <p class="text-sm text-gray-400 mb-3">{{ $p->buku->pengarang ?? '-' }}</p>
                            
                            <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm">
                                <div class="flex items-center gap-2 text-gray-300">
                                    <i class="fas fa-calendar-alt text-purple-400"></i>
                                    <span>Dipinjam: <strong class="text-white">{{ $p->tanggal_pinjam->format('d M Y') }}</strong></span>
                                </div>
                                <div class="flex items-center gap-2 {{ $isLate ? 'text-red-400' : 'text-gray-300' }}">
                                    <i class="fas fa-clock {{ $isLate ? 'text-red-400' : 'text-purple-400' }}"></i>
                                    <span>Batas Kembali: <strong class="{{ $isLate ? 'text-red-400' : 'text-white' }}">{{ $p->tanggal_kembali_rencana->format('d M Y') }}</strong></span>
                                </div>
                                
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                    {{ $statusText }}
                                </span>

                                @if($isLate)
                                <div class="flex items-center gap-2 text-amber-400 font-semibold">
                                    <i class="fas fa-coins"></i>
                                    <span>Est. Denda: Rp {{ number_format($dendaSaatIni, 0, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>
                            
                            @if($p->status_peminjaman == 'menunggu_konfirmasi')
                                <p class="text-xs text-amber-400 mt-2 bg-amber-500/10 inline-block px-2 py-1 rounded">
                                    <i class="fas fa-info-circle mr-1"></i> Pengajuan Anda sedang diverifikasi petugas.
                                </p>
                            @elseif($p->status_peminjaman == 'ditolak')
                                <p class="text-xs text-red-400 mt-2 bg-red-500/10 inline-block px-2 py-1 rounded">
                                    <i class="fas fa-ban mr-1"></i> {{ $p->catatan ?? 'Peminjaman ditolak oleh petugas.' }}
                                </p>
                            @endif
                        </div>

                        <!-- Action Buttons (PERBAIKAN DI SINI) -->
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            
                            <!-- Tombol Bayar Denda (Penyempurnaan: Open Modal QRIS) -->
                            @if($isLate && ($totalDenda > 0 || $dendaSaatIni > 0))
                                @php $denda = $p->denda->first(); @endphp
                                @if($denda)
                                    <button @click="openModal({{ $denda->id_denda }}, {{ $denda->jumlah_denda }}, '{{ addslashes($p->buku->judul) }}')" 
                                            class="flex-1 md:flex-none px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition shadow-lg shadow-emerald-500/20 text-center whitespace-nowrap flex items-center gap-2">
                                        <i class="fas fa-qrcode"></i>Bayar Denda
                                    </button>
                                @endif
                            @endif

                            <!-- Logika Tombol Utama -->
                            @if($p->status_peminjaman == 'menunggu_konfirmasi' || $p->status_peminjaman == 'ditolak')
                                <!-- Jika Menunggu Konfirmasi atau ditolak: Tombol Disabled -->
                                <button disabled class="flex-1 md:flex-none px-6 py-3 bg-slate-800 text-slate-500 font-semibold rounded-xl cursor-not-allowed text-center border border-slate-700 flex items-center justify-center gap-2">
                                    <i class="fas {{ $p->status_peminjaman == 'ditolak' ? 'fa-ban' : 'fa-hourglass-half' }}"></i> {{ $p->status_peminjaman == 'ditolak' ? 'Ditolak' : 'Menunggu Petugas' }}
                                </button>
                            
                            @elseif($p->status_peminjaman == 'dipinjam' || $isLate)
                                <!-- Jika Sedang Dipinjam: Tombol Instruksi (Bukan Aksi Langsung) -->
                                <button type="button" onclick="showReturnInstruction('{{ addslashes($p->buku->judul) }}')" 
                                        class="flex-1 md:flex-none px-6 py-3 bg-transparent border border-purple-500/50 text-purple-400 hover:bg-purple-500/10 hover:text-purple-300 font-semibold rounded-xl transition text-center flex items-center justify-center gap-2">
                                    <i class="fas fa-info-circle"></i> Cara Kembalikan
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <!-- Empty State -->
                    <div class="text-center py-16 bg-[#0a0a0a] border border-white/5 rounded-2xl">
                        <div class="w-20 h-20 bg-purple-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book-reader text-3xl text-purple-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Tidak ada peminjaman aktif</h3>
                        <p class="text-gray-400 mb-6">Anda sedang tidak meminjam buku apapun saat ini.</p>
                        <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-semibold rounded-full transition">
                            <i class="fas fa-search"></i> Cari Buku Sekarang
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="py-12">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-purple-900/20 to-indigo-900/20 border border-white/10 rounded-3xl p-12 text-center relative overflow-hidden">
                <div class="relative z-10">
                    <div class="w-16 h-16 mx-auto mb-6 bg-purple-600/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-book-open text-3xl text-purple-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Ingin pinjam buku lagi?</h3>
                    <p class="text-gray-400 mb-8 max-w-md mx-auto">Jelajahi katalog dan temukan buku bacaan selanjutnya.</p>
                    <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold rounded-full shadow-xl shadow-purple-500/25 transition transform hover:scale-105">
                        <i class="fas fa-search"></i>
                        Cari Buku
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- INSTRUCTION MODAL (RESTORED) -->
<div x-data="{ open: false, judul: '' }"
     @open-return-info.window="open = true; judul = $event.detail.judul"
     x-show="open"
     x-cloak
     class="fixed inset-0 z-[110] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-md" @click="open = false"></div>
    <div class="relative z-10 w-full max-w-md bg-[#111] border border-white/10 rounded-[40px] shadow-2xl overflow-hidden animate-scale-in">
        <div class="h-1 bg-indigo-500"></div>
        <div class="px-8 py-10 text-center">
            <div class="w-16 h-16 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-6"><i class="fas fa-info-circle text-2xl text-indigo-400"></i></div>
            <h3 class="text-xl font-black text-white mb-4">Cara Mengembalikan</h3>
            <p class="text-gray-400 text-sm mb-6 leading-relaxed">Untuk buku <span class="text-white font-bold" x-text="'\'' + judul + '\''"></span>, kunjungi perpustakaan dan serahkan ke petugas di meja administrasi.</p>
            <button @click="open = false" class="w-full py-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl transition-all">SAYA MENGERTI</button>
        </div>
    </div>
</div>

<!-- MODAL PEMBAYARAN QRIS (UNIFIED) -->
<div x-show="modalOpen" 
     x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="modalOpen = false"></div>
    <div class="relative bg-[#1e293b] border border-white/10 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-scale-in">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-black text-white tracking-tight">Pembayaran Denda</h3>
                    <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold" x-text="'Litearatur: ' + activeBuku"></p>
                </div>
                <button @click="modalOpen = false" class="text-slate-500 hover:text-white transition"><i class="fas fa-times"></i></button>
            </div>
            <div class="bg-white rounded-2xl p-4 mb-6 flex flex-col items-center border-4 border-indigo-500/20">
                @php
                    $qrisPath = \App\Models\Setting::where('key', 'qris_image_path')->first()->value ?? 'qris/default-qris.png';
                @endphp
                <img src="{{ asset('storage/' . $qrisPath) }}" alt="QRIS" class="w-44 h-44 mb-3">
                <div class="text-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Tagihan</p>
                    <p class="text-3xl font-black text-slate-900" x-text="formatRupiah(activeAmount)"></p>
                </div>
            </div>
            <form :action="'/anggota/denda/upload-bukti/' + activeDendaId" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="id_denda" :value="activeDendaId">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Upload Bukti Transfer</label>
                    <div class="relative group">
                        <input type="file" name="bukti_foto" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileName = $event.target.files[0].name">
                        <div class="w-full px-4 py-5 bg-slate-900/50 border-2 border-dashed border-slate-700/50 group-hover:border-indigo-500 rounded-2xl flex flex-col items-center justify-center transition-all">
                            <i class="fas fa-file-invoice text-2xl text-slate-600 group-hover:text-indigo-400 mb-2"></i>
                            <span class="text-xs font-bold text-slate-400" x-text="fileName || 'Pilih Gambar Bukti Transaksi'"></span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full py-5 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-2xl shadow-xl shadow-indigo-600/20 transition-all active:scale-95">SAYAH SUDAH BAYAR</button>
            </form>
        </div>
    </div>
</div>

<!-- Script Pembayaran -->
<script>
    function riwayatPayment() {
        return {
            modalOpen: false,
            fileName: '',
            activeDendaId: null,
            activeAmount: 0,
            activeBuku: '',
            openModal(id, amount, buku) {
                this.activeDendaId = id;
                this.activeAmount = amount;
                this.activeBuku = buku;
                this.fileName = '';
                this.modalOpen = true;
            },
            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            }
        }
    }

    function showReturnInstruction(judulBuku) {
        window.dispatchEvent(new CustomEvent('open-return-info', { detail: { judul: judulBuku } }));
    }
</script>
@endsection