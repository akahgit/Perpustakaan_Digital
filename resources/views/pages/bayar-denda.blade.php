@extends('layouts.app')

@section('title', 'Bayar Denda — Perpustakaan Digital')
@section('page-title', 'Bayar Denda via QRIS')

@section('content')
<div class="max-w-lg mx-auto animate-fade-in-down">

    {{-- Back --}}
    <a href="{{ route('peminjaman') }}"
       class="inline-flex items-center gap-2 text-xs text-slate-500 hover:text-indigo-400 transition font-medium mb-5">
        <i class="fas fa-arrow-left text-[10px]"></i> Kembali
    </a>

    {{-- Info Tagihan --}}
    <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl p-5 mb-5">
        <h2 class="text-base font-bold text-white mb-4">Detail Tagihan Denda</h2>
        <div class="space-y-2.5">
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Buku</span>
                <span class="text-slate-200 font-medium text-right">{{ $denda->peminjaman?->buku?->judul ?? '—' }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Terlambat</span>
                <span class="text-amber-400 font-bold">{{ $denda->hari_terlambat }} hari</span>
            </div>
            <div class="border-t border-white/5 pt-2.5 flex justify-between">
                <span class="text-sm font-bold text-white">Total Denda</span>
                <span class="text-xl font-extrabold text-rose-400">Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- QRIS Card --}}
    <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl overflow-hidden mb-5">
        <div class="h-0.5 bg-gradient-to-r from-indigo-500 via-violet-500 to-indigo-500"></div>
        <div class="p-6 text-center">
            <div class="inline-block bg-white p-4 rounded-2xl shadow-lg mb-4">
                {{-- QR Code Placeholder — ganti dengan gambar QRIS nyata --}}
                @php
                    $qrisPath = \App\Models\Setting::where('key', 'qris_image_path')->first()->value ?? 'qris/default-qris.png';
                @endphp
                <img src="{{ asset('storage/' . $qrisPath) }}" alt="QRIS Perpustakaan" class="w-44 h-44 object-contain rounded-xl">
            </div>
            <h3 class="text-sm font-bold text-white mb-1">Scan QRIS untuk Membayar</h3>
            <p class="text-xs text-slate-500 max-w-xs mx-auto leading-relaxed">
                Buka aplikasi dompet digital kamu (GoPay, OVO, Dana, dll.) dan scan kode QR di atas untuk membayar.
            </p>
        </div>

        {{-- Langkah --}}
        <div class="px-6 pb-4">
            <div class="bg-white/3 border border-white/5 rounded-xl p-4 space-y-3">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Cara Pembayaran:</p>
                @foreach(['Scan QR Code di atas dengan app dompet digital.', 'Pastikan nominal yang dibayar TEPAT: Rp ' . number_format($denda->jumlah_denda, 0, ',', '.'), 'Screenshot bukti pembayaran dari aplikasi.', 'Upload foto bukti di form bawah ini.'] as $i => $step)
                <div class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-indigo-600/30 text-indigo-400 text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5">{{ $i + 1 }}</span>
                    <p class="text-xs text-slate-400">{{ $step }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Status bukti sebelumnya --}}
    @if($denda->status_verifikasi === 'pending')
    <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-4 mb-5 flex items-start gap-3">
        <i class="fas fa-hourglass-half text-amber-400 mt-0.5"></i>
        <div>
            <p class="text-sm font-bold text-amber-300">Bukti Sedang Diverifikasi</p>
            <p class="text-xs text-slate-400 mt-0.5">Pembayaranmu sudah kami terima dan sedang diproses oleh petugas. Harap tunggu konfirmasi.</p>
        </div>
    </div>
    @endif

    @if($denda->status_verifikasi === 'rejected')
    <div class="bg-rose-500/10 border border-rose-500/20 rounded-2xl p-4 mb-5">
        <p class="text-sm font-bold text-rose-300 mb-1">⚠ Bukti Ditolak Petugas</p>
        <p class="text-xs text-slate-400">Alasan: <em>{{ $denda->catatan_petugas }}</em></p>
        <p class="text-xs text-slate-500 mt-1">Silakan upload ulang bukti yang benar.</p>
    </div>
    @endif

    {{-- Form Upload Bukti --}}
    @if($denda->status_verifikasi !== 'approved')
    <div class="bg-[#1e293b] rounded-2xl border border-white/5 shadow-xl p-6">
        <h3 class="text-sm font-bold text-white mb-4">Upload Bukti Pembayaran</h3>

        <form action="{{ route('denda.bayar.upload', $denda->id_denda) }}" method="POST" enctype="multipart/form-data" x-data="uploadForm()">
            @csrf

            {{-- Error --}}
            @error('bukti_foto')
            <div class="bg-rose-500/10 border border-rose-500/20 rounded-xl p-3 mb-4 text-xs text-rose-300">
                {{ $message }}
            </div>
            @enderror

            {{-- Drop Zone --}}
            <div class="border-2 border-dashed border-white/10 hover:border-indigo-500/40 rounded-xl p-6 text-center transition-all"
                 @dragover.prevent=""
                 @drop.prevent="handleDrop($event)">
                <input type="file" id="bukti_foto" name="bukti_foto"
                       accept="image/jpeg,image/png,image/jpg"
                       @change="handleFile($event)"
                       class="hidden">

                <div x-show="!preview">
                    <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-cloud-upload-alt text-indigo-400 text-xl"></i>
                    </div>
                    <p class="text-sm text-slate-400 mb-1">Drag & drop foto atau</p>
                    <label for="bukti_foto"
                           class="cursor-pointer inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl transition">
                        <i class="fas fa-image"></i> Pilih File
                    </label>
                    <p class="text-[10px] text-slate-600 mt-2">JPG, PNG — Maks. 2 MB</p>
                </div>

                <div x-show="preview" class="relative">
                    <img :src="preview" alt="Preview" class="max-h-48 mx-auto rounded-xl border border-white/10 object-contain">
                    <button @click="preview = null" type="button"
                            class="absolute -top-2 -right-2 w-6 h-6 bg-rose-600 rounded-full flex items-center justify-center text-white text-xs hover:bg-rose-500 transition">
                        <i class="fas fa-times"></i>
                    </button>
                    <p class="text-xs text-emerald-400 mt-2" x-text="fileName"></p>
                </div>
            </div>

            <button type="submit" :disabled="!preview"
                    class="mt-4 w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/25 transition disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane"></i> Kirim Bukti Pembayaran
            </button>
        </form>
    </div>
    @else
    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-5 text-center">
        <i class="fas fa-circle-check text-emerald-400 text-3xl mb-3"></i>
        <p class="text-sm font-bold text-emerald-300">Denda Sudah Lunas!</p>
        <p class="text-xs text-slate-400 mt-1">Terima kasih telah melunasi denda.</p>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function uploadForm() {
        return {
            preview: null,
            fileName: '',
            handleFile(e) {
                const file = e.target.files[0];
                if (file) { this.preview = URL.createObjectURL(file); this.fileName = file.name; }
            },
            handleDrop(e) {
                const file = e.dataTransfer.files[0];
                if (file) {
                    const input = document.getElementById('bukti_foto');
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;
                    this.preview = URL.createObjectURL(file);
                    this.fileName = file.name;
                }
            }
        };
    }
</script>
@endpush
