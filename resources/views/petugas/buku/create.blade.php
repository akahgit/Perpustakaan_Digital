@extends('layouts.petugas')

@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku Baru')

@section('content')
<div class="max-w-4xl mx-auto bg-[#1e293b] p-8 rounded-2xl border border-slate-700/50">
    <form action="{{ route('petugas.buku.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Judul -->
            <div class="md:col-span-2">
                <label class="block text-sm text-slate-300 mb-2">Judul Buku *</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
                @error('judul') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Pengarang & Penerbit -->
            <div>
                <label class="block text-sm text-slate-300 mb-2">Pengarang *</label>
                <input type="text" name="pengarang" value="{{ old('pengarang') }}" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-2">Penerbit *</label>
                <input type="text" name="penerbit" value="{{ old('penerbit') }}" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>

            <!-- Tahun & Stok -->
            <div>
                <label class="block text-sm text-slate-300 mb-2">Tahun Terbit *</label>
                <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', date('Y')) }}" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-2">Stok Awal *</label>
                <input type="number" name="stok" value="{{ old('stok', 1) }}" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>

            <!-- Kategori (PENTING: name=id_kategori) -->
            <div class="md:col-span-2">
                <label class="block text-sm text-slate-300 mb-2">Kategori *</label>
                <select name="id_kategori" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
                @error('id_kategori') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Cover (PENTING: name=cover_buku) -->
            <div class="md:col-span-2">
                <label class="block text-sm text-slate-300 mb-2">Cover Buku</label>
                <input type="file" name="cover_buku" accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:bg-blue-600 file:text-white file:border-0">
                @error('cover_buku') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <!-- Sinopsis -->
            <div class="md:col-span-2">
                <label class="block text-sm text-slate-300 mb-2">Sinopsis</label>
                <textarea name="sinopsis" rows="4" class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">{{ old('sinopsis') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-6 border-t border-slate-700">
            <a href="{{ route('petugas.buku.index') }}" class="px-6 py-3 rounded-xl border border-slate-600 text-slate-300 hover:bg-slate-800">Batal</a>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30">Simpan Buku</button>
        </div>
    </form>
</div>
@endsection