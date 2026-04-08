@extends('layouts.app')

@section('title', 'Profil Saya - Perpustakaan Digital')

@section('content')
<div class="bg-[#050505] min-h-screen pb-20">
    
    <!-- HEADER PROFIL -->
    <section class="pt-12 pb-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-purple-900/40 to-indigo-900/40 border border-white/10 rounded-3xl p-8 md:p-10 relative overflow-hidden">
                <!-- Background Decor -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-8">
                    <!-- Avatar Besar -->
                    <div class="relative">
                        <div class="w-32 h-32 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-5xl font-bold text-white shadow-2xl shadow-purple-500/30 ring-4 ring-white/10">
                            {{ strtoupper(substr($anggota->nama ?? auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="absolute bottom-2 right-2 w-6 h-6 {{ ($anggota->status ?? 'aktif') == 'aktif' ? 'bg-emerald-500' : 'bg-red-500' }} border-4 border-[#1a1a1a] rounded-full" title="{{ $anggota->status ?? 'Aktif' }}"></div>
                    </div>

                    <!-- Info Utama -->
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $anggota->nama ?? auth()->user()->name }}</h1>
                        <p class="text-gray-400 mb-4">Anggota sejak {{ $anggota->tanggal_bergabung ? $anggota->tanggal_bergabung->format('F Y') : 'Belum terdata' }}</p>
                        
                        <div class="flex flex-wrap justify-center md:justify-start gap-3 mb-6">
                            <span class="px-4 py-1.5 bg-white/5 border border-white/10 rounded-full text-sm font-medium text-gray-300 flex items-center gap-2">
                                <i class="fas fa-id-card text-purple-400"></i>
                                NIS/NIP: {{ $anggota->nis_nisn ?? '-' }}
                            </span>
                            <span class="px-4 py-1.5 {{ ($anggota->status ?? 'aktif') == 'aktif' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-red-500/10 border-red-500/20 text-red-400' }} border rounded-full text-sm font-medium flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                {{ ucfirst($anggota->status ?? 'Aktif') }}
                            </span>
                        </div>

                        <!-- Tombol Edit Profil (Memicu Modal atau Scroll ke Form) -->
                        <button onclick="document.getElementById('editForm').scrollIntoView({behavior: 'smooth'})" class="px-6 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-semibold rounded-xl transition flex items-center gap-2 mx-auto md:mx-0">
                            <i class="fas fa-edit"></i>
                            Edit Data Kontak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS GRID (DINAMIS) -->
    <section class="py-4">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            @php
                // Hitung statistik dari database
                $totalPinjam = \App\Models\Peminjaman::where('id_anggota', $anggota->id)->count();
                $sedangDipinjam = \App\Models\Peminjaman::where('id_anggota', $anggota->id)->whereIn('status_peminjaman', ['dipinjam', 'menunggu_konfirmasi'])->count();
                $dikembalikan = \App\Models\Peminjaman::where('id_anggota', $anggota->id)->where('status_peminjaman', 'dikembalikan')->count();
                
                // Hitung denda belum lunas
                $dendaAktif = \App\Models\Denda::whereHas('peminjaman', function($q) use ($anggota) {
                    $q->where('id_anggota', $anggota->id);
                })->where('status_pembayaran', 'belum_lunas')->sum('jumlah_denda');
            @endphp

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 text-center hover:border-purple-500/30 transition">
                    <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-book-open text-2xl text-purple-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1">{{ $totalPinjam }}</div>
                    <div class="text-sm text-gray-400">Total Peminjaman</div>
                </div>

                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 text-center hover:border-amber-500/30 transition">
                    <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clock text-2xl text-amber-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1">{{ $sedangDipinjam }}</div>
                    <div class="text-sm text-gray-400">Sedang Dipinjam</div>
                </div>

                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 text-center hover:border-emerald-500/30 transition">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-history text-2xl text-emerald-400"></i>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1">{{ $dikembalikan }}</div>
                    <div class="text-sm text-gray-400">Dikembalikan</div>
                </div>

                <div class="bg-[#0a0a0a] border {{ $dendaAktif > 0 ? 'border-red-500/40' : 'border-white/10' }} rounded-2xl p-6 text-center hover:border-red-500/40 transition">
                    <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-coins text-2xl text-red-400"></i>
                    </div>
                    <div class="text-3xl font-bold {{ $dendaAktif > 0 ? 'text-red-400' : 'text-white' }} mb-1">Rp {{ number_format($dendaAktif, 0, ',', '.') }}</div>
                    <div class="text-sm text-gray-400">Denda Aktif</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FORM EDIT DATA KONTAK -->
    <section class="py-8" id="editForm">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl flex items-center gap-3">
                    <i class="fas fa-check-circle text-xl"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                    <i class="fas fa-address-card text-indigo-400"></i>
                    Perbarui Informasi Kontak
                </h3>
                
                <form action="{{ route('profil.update') }}" method="POST">
                    @csrf
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Kolom Kiri: Data Readonly -->
                        <div class="space-y-5 opacity-75">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Nama Lengkap (Tidak dapat diubah)</label>
                                <input type="text" value="{{ $anggota->nama ?? auth()->user()->name }}" readonly 
                                       class="w-full bg-[#050505] border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">NIS / NIP (Tidak dapat diubah)</label>
                                <input type="text" value="{{ $anggota->nis_nisn ?? '-' }}" readonly 
                                       class="w-full bg-[#050505] border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Email (Hubungi Petugas untuk ubah)</label>
                                <input type="email" value="{{ $anggota->email ?? auth()->user()->email }}" readonly 
                                       class="w-full bg-[#050505] border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Kolom Kanan: Data Editable -->
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-medium text-gray-300 uppercase mb-1">Alamat Lengkap</label>
                                <textarea name="alamat" rows="3" 
                                          class="w-full bg-[#050505] border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-indigo-500 transition resize-none">{{ old('alamat', $anggota->alamat ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-300 uppercase mb-1">No. Telepon / WhatsApp</label>
                                <input type="text" name="no_telepon" value="{{ old('no_telepon', $anggota->no_telepon ?? '') }}" 
                                       class="w-full bg-[#050505] border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-indigo-500 transition">
                            </div>
                            
                            <div class="pt-4 flex justify-end">
                                <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition flex items-center gap-2">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- AKTIVITAS TERBARU (DINAMIS) -->
    <section class="py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-bolt text-amber-400"></i>
                        Aktivitas Terbaru
                    </h3>
                    <a href="{{ route('riwayat') }}" class="text-sm text-purple-400 hover:text-purple-300 font-medium flex items-center gap-1">
                        Lihat Semua Riwayat
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>

                <div class="space-y-4">
                    @php
                        // Ambil 4 aktivitas terakhir (Peminjaman & Pengembalian)
                        $activities = \App\Models\Peminjaman::with('buku')
                            ->where('id_anggota', $anggota->id)
                            ->orderBy('updated_at', 'desc')
                            ->limit(4)
                            ->get();
                    @endphp

                    @forelse($activities as $act)
                        @php
                            $isLate = $act->tanggal_kembali_realisasi && $act->tanggal_kembali_realisasi > $act->tanggal_kembali_rencana;
                            
                            if ($act->status_peminjaman == 'dikembalikan') {
                                $type = 'kembali';
                                $color = $isLate ? 'red' : 'emerald';
                                $icon = 'fa-check-double';
                                $desc = $isLate ? 'Terlambat ' . $act->tanggal_kembali_rencana->diffInDays($act->tanggal_kembali_realisasi, false) . ' hari' : 'Tepat waktu';
                                $date = $act->tanggal_kembali_realisasi->format('d M Y');
                            } else {
                                $type = 'pinjam';
                                $color = 'purple';
                                $icon = 'fa-hand-holding-heart';
                                $desc = 'Batas kembali: ' . $act->tanggal_kembali_rencana->format('d M Y');
                                $date = $act->tanggal_pinjam->format('d M Y');
                            }
                        @endphp
                        <div class="flex items-start gap-4 p-4 rounded-xl hover:bg-white/5 transition border border-transparent hover:border-white/5">
                            <!-- Icon -->
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 
                                @if($color == 'purple') bg-purple-500/10 text-purple-400
                                @elseif($color == 'emerald') bg-emerald-500/10 text-emerald-400
                                @else bg-red-500/10 text-red-400
                                @endif">
                                <i class="fas {{ $icon }} text-xl"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 pt-1">
                                <h4 class="text-white font-medium mb-1">
                                    @if($type == 'pinjam')
                                        Meminjam buku "<span class="text-white">{{ $act->buku->judul ?? 'Buku Dihapus' }}</span>"
                                    @else
                                        Mengembalikan buku "<span class="text-white">{{ $act->buku->judul ?? 'Buku Dihapus' }}</span>"
                                    @endif
                                </h4>
                                <p class="text-sm text-gray-400">{{ $date }} • {{ $desc }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <p>Belum ada aktivitas tercatat.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
@endsection