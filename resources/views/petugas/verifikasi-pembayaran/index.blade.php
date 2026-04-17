@extends('layouts.petugas')

@section('title', 'Verifikasi Pembayaran Denda')
@section('page-title', 'Verifikasi Pembayaran')
@section('page-subtitle', 'Konfirmasi bukti transfer QRIS dari anggota')

@section('content')
<div class="space-y-5 animate-fade-in-down"
     x-data="verifikasiPage()"
     x-init="init()">

    {{-- ══ STATISTIK ══ --}}
    <div class="grid grid-cols-3 gap-4">
        <a href="{{ route('petugas.pembayaran.index', ['status' => 'pending']) }}"
           class="bg-[#1e293b] rounded-2xl p-5 border {{ $filterStatus === 'pending' ? 'border-amber-500/40' : 'border-white/5' }} shadow-xl hover:border-amber-500/30 transition">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Menunggu</p>
            <span class="text-3xl font-extrabold {{ $countPending > 0 ? 'text-amber-400' : 'text-white' }}">{{ $countPending }}</span>
        </a>
        <a href="{{ route('petugas.pembayaran.index', ['status' => 'approved']) }}"
           class="bg-[#1e293b] rounded-2xl p-5 border {{ $filterStatus === 'approved' ? 'border-emerald-500/40' : 'border-white/5' }} shadow-xl hover:border-emerald-500/30 transition">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Disetujui</p>
            <span class="text-3xl font-extrabold text-emerald-400">{{ $countApproved }}</span>
        </a>
        <a href="{{ route('petugas.pembayaran.index', ['status' => 'rejected']) }}"
           class="bg-[#1e293b] rounded-2xl p-5 border {{ $filterStatus === 'rejected' ? 'border-rose-500/40' : 'border-white/5' }} shadow-xl hover:border-rose-500/30 transition">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Ditolak</p>
            <span class="text-3xl font-extrabold text-rose-400">{{ $countRejected }}</span>
        </a>
    </div>

    {{-- ══ TABEL BUKTI PEMBAYARAN ══ --}}
    <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-white">Bukti Pembayaran Masuk</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Filter: <strong class="text-indigo-400">{{ ucfirst($filterStatus ?? 'Semua') }}</strong>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('petugas.pembayaran.index', ['status' => 'pending']) }}"
                   class="px-3 py-1.5 text-xs font-bold rounded-lg {{ $filterStatus === 'pending' ? 'bg-amber-500 text-white' : 'bg-white/5 text-slate-400 hover:text-white border border-white/8' }} transition">
                    Pending
                </a>
                <a href="{{ route('petugas.pembayaran.index', ['status' => 'semua']) }}"
                   class="px-3 py-1.5 text-xs font-bold rounded-lg {{ $filterStatus === 'semua' ? 'bg-indigo-500 text-white' : 'bg-white/5 text-slate-400 hover:text-white border border-white/8' }} transition">
                    Semua
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/3 border-b border-white/5">
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Anggota</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em]">Buku</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Jumlah</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Bukti</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Status</th>
                        <th class="px-5 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.12em] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/3">
                    @forelse($dendas as $denda)
                    <tr class="table-row-hover transition-colors group">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">
                                    {{ strtoupper(substr($denda->peminjaman?->anggota?->nama ?? 'NA', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-200">{{ $denda->peminjaman?->anggota?->nama ?? '—' }}</div>
                                    <div class="text-[10px] text-slate-600">{{ $denda->peminjaman?->anggota?->kelas ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 max-w-[160px]">
                            <div class="text-xs text-slate-300 truncate">{{ $denda->peminjaman?->buku?->judul ?? '—' }}</div>
                            <div class="text-[10px] text-slate-600">{{ $denda->hari_terlambat }} hari terlambat</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="text-sm font-bold text-rose-400">Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($denda->bukti_foto)
                                <button @click="openBukti('{{ Storage::url($denda->bukti_foto) }}')"
                                        class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500 border border-blue-500/20 text-blue-400 hover:text-white flex items-center justify-center mx-auto transition"
                                        title="Lihat Bukti">
                                    <i class="fas fa-image text-xs"></i>
                                </button>
                            @else
                                <span class="text-xs text-slate-600">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($denda->status_verifikasi === 'pending')
                                <span class="badge-warning inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold animate-pulse">
                                    <i class="fas fa-circle text-[4px]"></i> Menunggu
                                </span>
                            @elseif($denda->status_verifikasi === 'approved')
                                <span class="badge-success inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                    <i class="fas fa-circle text-[4px]"></i> Diterima
                                </span>
                            @elseif($denda->status_verifikasi === 'rejected')
                                <span class="badge-error inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                    <i class="fas fa-circle text-[4px]"></i> Ditolak
                                </span>
                            @else
                                <span class="badge-muted inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                    <i class="fas fa-circle text-[4px]"></i> —
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($denda->status_verifikasi === 'pending')
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- TERIMA --}}
                                <button type="button"
                                        @click="window.dispatchEvent(new CustomEvent('confirm', { 
                                            detail: {
                                                title: 'Terima Pembayaran?',
                                                message: 'Anda akan menyetujui bukti pembayaran ini. Status denda akan berubah menjadi <strong>LUNAS</strong>.',
                                                action: '{{ route('petugas.pembayaran.terima', $denda->id_denda) }}',
                                                type: 'success',
                                                confirmText: 'Ya, Terima',
                                                usePin: false
                                            }
                                        }))"
                                        class="w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 border border-emerald-500/20 text-emerald-400 hover:text-white flex items-center justify-center transition"
                                        title="Terima Pembayaran">
                                    <i class="fas fa-check text-xs"></i>
                                </button>

                                {{-- TOLAK --}}
                                <button @click="openTolakModal({{ $denda->id_denda }}, '{{ route('petugas.pembayaran.tolak', $denda->id_denda) }}')"
                                        class="w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 border border-rose-500/20 text-rose-400 hover:text-white flex items-center justify-center transition"
                                        title="Tolak Pembayaran">
                                    <i class="fas fa-xmark text-xs"></i>
                                </button>
                            </div>
                            @else
                                <span class="text-xs text-slate-600">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-14 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 bg-white/3 rounded-2xl flex items-center justify-center mb-4">
                                    <i class="fas fa-receipt text-slate-600 text-2xl"></i>
                                </div>
                                <p class="text-sm text-slate-400">Tidak ada bukti pembayaran.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dendas->hasPages())
        <div class="px-5 py-3 border-t border-white/5">
            {{ $dendas->links() }}
        </div>
        @endif
    </div>

    {{-- ══ MODAL LIHAT BUKTI ══ --}}
    <div x-show="buktiModal.open" x-cloak
         class="fixed inset-0 z-[999] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 modal-backdrop" @click="buktiModal.open = false"></div>
        <div class="relative z-10 max-w-lg w-full bg-[#1e293b] border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in">
            <div class="px-5 py-3.5 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-sm font-bold text-white">Bukti Pembayaran</h3>
                <button @click="buktiModal.open = false" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-500 hover:text-white hover:bg-white/10 transition">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <div class="p-4">
                <img :src="buktiModal.url" alt="Bukti Pembayaran" class="w-full rounded-xl border border-white/10 max-h-96 object-contain bg-slate-900">
            </div>
        </div>
    </div>

    {{-- ══ MODAL TOLAK ══ --}}
    <div x-show="tolakModal.open" x-cloak
         class="fixed inset-0 z-[999] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="absolute inset-0 modal-backdrop" @click="tolakModal.open = false"></div>
        <div class="relative z-10 w-full max-w-md bg-[#1e293b] border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in">
            <div class="h-0.5 bg-gradient-to-r from-rose-600 to-red-500"></div>
            <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-base font-bold text-white">Tolak Bukti Pembayaran</h3>
                <button @click="tolakModal.open = false" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-500 hover:text-white hover:bg-white/10 transition">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form :action="tolakModal.action" id="form-tolak-pembayaran" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="bg-rose-500/10 border border-rose-500/20 rounded-xl p-3">
                    <p class="text-xs text-rose-300">Bukti yang ditolak akan dihapus. Anggota dapat mengunggah ulang.</p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Alasan Penolakan *</label>
                    <textarea name="catatan_petugas" rows="3" required
                              class="w-full px-4 py-2.5 bg-slate-900/60 border border-white/8 rounded-xl text-sm text-white placeholder-slate-500
                                     focus:outline-none focus:border-rose-500/60 focus:ring-2 focus:ring-rose-500/20 transition resize-none"
                              placeholder="mis: Nominal tidak sesuai, bukti buram, dst."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="tolakModal.open = false"
                            class="flex-1 px-4 py-2.5 bg-white/5 border border-white/10 text-slate-300 text-sm font-semibold rounded-xl hover:bg-white/10 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white text-sm font-bold rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-xmark"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function verifikasiPage() {
        return {
            buktiModal: { open: false, url: '' },
            tolakModal: { open: false, action: '' },
            init() {},
            openBukti(url) { this.buktiModal = { open: true, url }; },
            openTolakModal(id, action) { this.tolakModal = { open: true, action }; },
        };
    }
</script>
@endpush
