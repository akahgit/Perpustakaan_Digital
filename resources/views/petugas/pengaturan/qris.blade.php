@extends('layouts.petugas')

@section('title', 'Pengaturan QRIS — Petugas')
@section('page-title', 'Konfigurasi QRIS')
@section('page-subtitle', 'Update gambar QRIS pembayaran untuk denda anggota')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <div class="bg-[#1e293b] rounded-[32px] border border-white/5 shadow-2xl overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
        
        <div class="p-8 md:p-12">
            <div class="flex items-center gap-4 mb-10">
                <div class="w-14 h-14 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-400">
                    <i class="fas fa-qrcode text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-white">QRIS Dinamis</h3>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">Merchant Payment Config</p>
                </div>
            </div>

            <form action="{{ route('petugas.setting.qris.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                {{-- Preview Section --}}
                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">QRIS Saat Ini</label>
                    <div class="relative group w-48 mx-auto md:mx-0">
                        <div class="aspect-square rounded-3xl overflow-hidden border-2 border-dashed border-white/10 bg-black/20 flex items-center justify-center">
                            @if($qrisSetting && $qrisSetting->value)
                                <img src="{{ asset('storage/' . $qrisSetting->value) }}" alt="Current QRIS" class="w-full h-full object-contain p-4 group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="text-center p-6">
                                    <i class="fas fa-image text-slate-700 text-3xl mb-2"></i>
                                    <p class="text-[10px] text-slate-600 font-bold">No Image Found</p>
                                </div>
                            @endif
                        </div>
                        <div class="absolute -bottom-3 -right-3 w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg border border-white/10">
                            <i class="fas fa-eye text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- Upload Section --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Upload Gambar Baru</label>
                    <div class="relative">
                        <input type="file" name="qris_image" required
                               class="w-full text-sm text-slate-400 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-xs file:font-black file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20 transition-all cursor-pointer bg-black/20 rounded-2xl border border-white/5 pr-4 py-1">
                    </div>
                    <p class="text-[10px] text-slate-600 font-medium ml-1 italic">Format: PNG, JPG (Maks. 2MB). Rekomendasi aspek rasio 1:1.</p>
                    @error('qris_image')
                        <p class="text-rose-400 text-xs mt-1 ml-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" 
                            class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/25 transition-all hover:-translate-y-1 flex items-center justify-center gap-3">
                        <i class="fas fa-save text-sm opacity-50"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="mt-8 p-6 bg-amber-500/5 border border-amber-500/10 rounded-3xl flex items-start gap-4">
        <i class="fas fa-info-circle text-amber-500 mt-1"></i>
        <div class="space-y-1">
            <h4 class="text-xs font-black text-amber-400 uppercase tracking-widest">Pemberitahuan</h4>
            <p class="text-[11px] text-amber-500/70 leading-relaxed">Pastikan gambar QRIS yang diupload adalah resmi dari penyedia layanan pembayaran Anda. Perubahan ini akan langsung berdampak pada halaman pembayaran denda di sisi Anggota.</p>
        </div>
    </div>

</div>
@endsection
