@extends('layouts.app')

@section('title', 'Riwayat Transaksi - Perpustakaan Digital')

@section('content')
<div class="bg-[#050505] min-h-screen pb-20" x-data="riwayatPayment()">
    
    <!-- HEADER SECTION -->
    <section class="pt-12 pb-8 border-b border-white/5">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl lg:text-5xl font-bold mb-2">
                Riwayat <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-400">Transaksi</span>
            </h1>
            <p class="text-gray-400 text-lg">Pantau seluruh riwayat peminjaman dan pengembalian buku Anda.</p>
        </div>
    </section>

    <!-- STATS SUMMARY (DINAMIS) -->
    <section class="py-8">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            @php
                // Hitung statistik sederhana dari data riwayat yang ada
                $totalPinjam = $riwayats->total(); 
                $totalDendaHistorical = 0;
                $tepatWaktu = 0;
                $terlambat = 0;

                foreach($riwayats as $r) {
                    if (!$r->buku) continue; 

                    if ($r->tanggal_kembali_realisasi && $r->tanggal_kembali_realisasi > $r->tanggal_kembali_rencana) {
                        $terlambat++;
                        $daysLate = $r->tanggal_kembali_rencana->diffInDays($r->tanggal_kembali_realisasi, false);
                        $totalDendaHistorical += ($daysLate * 1000); 
                    } else {
                        $tepatWaktu++;
                    }
                }
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-purple-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-history text-purple-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Riwayat</span>
                    </div>
                    <div class="text-2xl font-bold text-white">{{ $totalPinjam }}</div>
                </div>
                <div class="bg-[#0a0a0a] border border-emerald-500/20 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-emerald-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-emerald-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Tepat Waktu</span>
                    </div>
                    <div class="text-2xl font-bold text-emerald-400">{{ $tepatWaktu }}</div>
                </div>
                <div class="bg-[#0a0a0a] border border-red-500/20 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-red-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-red-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Terlambat</span>
                    </div>
                    <div class="text-2xl font-bold text-red-400">{{ $terlambat }}</div>
                </div>
                <div class="bg-[#0a0a0a] border border-amber-500/20 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-amber-500/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-coins text-amber-400 text-sm"></i>
                        </div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Est. Denda Historis</span>
                    </div>
                    <div class="text-2xl font-bold text-amber-400">Rp {{ number_format($totalDendaHistorical, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- TABEL RIWAYAT UTAMA -->
    <section class="py-4">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/10 text-xs uppercase tracking-wider text-gray-400">
                                <th class="px-6 py-4 font-semibold">ID</th>
                                <th class="px-6 py-4 font-semibold">Buku</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Keterlambatan</th>
                                <th class="px-6 py-4 font-semibold text-right">Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm">
                            @forelse($riwayats as $r)
                                @php
                                    if (!$r->buku) continue;

                                    $denda = $r->denda->first();
                                    $isLate = $r->tanggal_kembali_realisasi && $r->tanggal_kembali_realisasi > $r->tanggal_kembali_rencana;
                                    
                                    $colors = ['from-purple-500 to-pink-600', 'from-blue-500 to-cyan-600', 'from-indigo-500 to-blue-600', 'from-emerald-500 to-teal-600'];
                                    $colorClass = $colors[$r->buku->id_buku % count($colors)] ?? 'from-gray-500 to-gray-600';
                                @endphp
                                <tr class="hover:bg-white/5 transition group">
                                    <td class="px-6 py-4 font-mono text-purple-400">#PMJ-{{ str_pad($r->id_peminjaman, 4, '0', STR_PAD_LEFT) }}</td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-14 bg-gradient-to-br {{ $colorClass }} rounded flex-shrink-0 flex items-center justify-center">
                                                @if($r->buku->cover_buku && file_exists(public_path('storage/' . $r->buku->cover_buku)))
                                                    <img src="{{ asset('storage/' . $r->buku->cover_buku) }}" class="w-full h-full object-cover rounded opacity-80">
                                                @else
                                                    <i class="fas fa-book text-white/40 text-xs"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-medium text-white group-hover:text-purple-300 transition">{{ $r->buku->judul }}</div>
                                                <div class="text-[10px] text-gray-500 uppercase tracking-tighter">{{ $r->created_at->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($isLate)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">TERLAMBAT</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">TEPAT WAKTU</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($isLate)
                                            @php 
                                                $calculatedDenda = $r->tanggal_kembali_rencana->diffInDays($r->tanggal_kembali_realisasi) * 1000;
                                            @endphp
                                            <div class="text-white font-bold">{{ $r->tanggal_kembali_rencana->diffInDays($r->tanggal_kembali_realisasi) }} Hari</div>
                                            <div class="text-[10px] text-gray-500">Denda: Rp {{ number_format($denda->jumlah_denda ?? $calculatedDenda, 0, ',', '.') }}</div>
                                        @else
                                            <span class="text-gray-600">—</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        @if($isLate && $denda)
                                            @if($denda->status_pembayaran === 'lunas')
                                                <div class="flex flex-col items-end">
                                                    <span class="text-emerald-400 font-bold flex items-center gap-1">
                                                        <i class="fas fa-check-circle text-[10px]"></i> Lunas
                                                    </span>
                                                    <span class="text-[10px] text-gray-600">{{ $denda->tanggal_bayar?->format('d/m/Y') }}</span>
                                                    <a href="{{ route('denda.receipt', $denda->id_denda) }}"
                                                       target="_blank"
                                                       class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 bg-white/5 hover:bg-white/10 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg border border-white/10 transition">
                                                        <i class="fas fa-print"></i>
                                                        Cetak Struk
                                                    </a>
                                                </div>
                                            @elseif($denda->status_verifikasi === 'pending')
                                                <span class="px-2 py-1 rounded-lg bg-indigo-500/10 text-indigo-400 text-[10px] font-bold border border-indigo-500/20">
                                                    Menunggu Verifikasi
                                                </span>
                                            @else
                                                <button @click="openModal({{ $denda->id_denda }}, {{ $denda->jumlah_denda }}, '{{ addslashes($r->buku->judul) }}')"
                                                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-emerald-600/20">
                                                    <i class="fas fa-qrcode"></i>
                                                    Bayar Denda
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-gray-600">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        Belum ada riwayat transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- MODAL PEMBAYARAN QRIS -->
    <div x-show="modalOpen" 
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="modalOpen = false"></div>

        <!-- Content -->
        <div class="relative bg-[#1e293b] border border-white/10 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl animate-scale-in">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-white tracking-tight">Pembayaran QRIS</h3>
                        <p class="text-xs text-slate-400 mt-1" x-text="'Buku: ' + activeBuku"></p>
                    </div>
                    <button @click="modalOpen = false" class="text-slate-500 hover:text-white transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- QR Area -->
                <div class="bg-white rounded-2xl p-4 mb-6 flex flex-col items-center">
                    @php
                        $qrisPath = \App\Models\Setting::where('key', 'qris_image_path')->first()->value ?? 'qris/default-qris.png';
                    @endphp
                    <img src="{{ asset('storage/' . $qrisPath) }}" 
                         alt="QRIS"
                         class="w-48 h-48 mb-3">
                    <div class="text-center">
                        <p class="text-xs font-bold text-slate-500 uppercase">Total Denda</p>
                        <p class="text-2xl font-black text-slate-900" x-text="formatRupiah(activeAmount)"></p>
                    </div>
                </div>

                <!-- Form -->
                <form :action="'/anggota/denda/upload-bukti/' + activeDendaId" method="POST" enctype="multipart/form-data" 
                      class="space-y-4" id="uploadForm">
                    @csrf
                    <input type="hidden" name="id_denda" :value="activeDendaId">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 tracking-wider">Upload Bukti Transfer</label>
                        <div class="relative group">
                            <input type="file" name="bukti_foto" required
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                   @change="fileName = $event.target.files[0].name">
                            <div class="w-full px-4 py-4 bg-slate-900/50 border-2 border-dashed border-slate-700/50 group-hover:border-indigo-500/50 rounded-2xl flex flex-col items-center justify-center transition-all duration-300">
                                <i class="fas fa-cloud-arrow-up text-2xl text-slate-600 group-hover:text-indigo-400 mb-2"></i>
                                <span class="text-sm font-medium text-slate-400" x-text="fileName || 'Klik atau seret file ke sini'"></span>
                                <span class="text-[10px] text-slate-600 mt-1">PNG, JPG, JPEG (Max 2MB)</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl shadow-xl shadow-indigo-600/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Bukti Pembayaran
                    </button>
                </form>

                <!-- Instructions -->
                <div class="mt-6 p-4 bg-white/5 rounded-2xl border border-white/5">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase mb-2">Instruksi Pembayaran:</h4>
                    <ul class="text-[11px] text-slate-300 space-y-1">
                        <li>1. Scan QR Code di atas melalui e-wallet / mobile banking.</li>
                        <li>2. Pastikan nominal sesuai dengan denda yang tercantum.</li>
                        <li>3. Screenshot bukti transfer berhasil.</li>
                        <li>4. Unggah screenshot tersebut pada form di atas.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
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
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            }
        }
    }
</script>
@endpush
@endsection
