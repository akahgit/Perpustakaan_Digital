@extends('layouts.petugas')

@section('title', 'Edit Buku')
@section('page-title', 'Edit Data Buku')

@section('content')
<div class="max-w-4xl mx-auto bg-[#1e293b] p-8 rounded-2xl border border-slate-700/50">
    <form action="{{ route('petugas.buku.update', ['data_buku' => $data_buku->id_buku]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Judul -->
            <div class="md:col-span-2">
                <label class="block text-sm text-slate-300 mb-2">Judul Buku *</label>
                <input type="text" name="judul" value="{{ old('judul', $data_buku->judul) }}" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>

            <!-- Pengarang & Penerbit -->
            <div>
                <label class="block text-sm text-slate-300 mb-2">Pengarang *</label>
                <input type="text" name="pengarang" value="{{ old('pengarang', $data_buku->pengarang) }}" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-2">Penerbit *</label>
                <input type="text" name="penerbit" value="{{ old('penerbit', $data_buku->penerbit) }}" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>

            <!-- ISBN -->
            <div class="md:col-span-2">
                <label class="block text-sm text-slate-300 mb-2">ISBN</label>
                <input type="text" name="isbn" value="{{ old('isbn', $data_buku->isbn) }}" class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none" placeholder="Contoh: 978-623-123-456-7">
                @error('isbn') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Tahun & Stok -->
            <div>
                <label class="block text-sm text-slate-300 mb-2">Tahun Terbit *</label>
                <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $data_buku->tahun_terbit) }}" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-2">Stok *</label>
                <input type="number" name="stok" value="{{ old('stok', $data_buku->stok) }}" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm text-slate-300 mb-2">Harga Buku / Ganti *</label>
                <input type="number" name="harga_ganti" value="{{ old('harga_ganti', $data_buku->harga_ganti ?? 50000) }}" min="0" step="1000" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
            </div>
            <div class="flex items-end">
                <p class="text-xs text-slate-500">Akan digunakan sebagai nominal denda saat buku dinyatakan hilang.</p>
            </div>

            <!-- Kategori -->
            <div class="md:col-span-2">
                <label class="block text-sm text-slate-300 mb-2">Kategori *</label>
                <select name="id_kategori" required class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id_kategori }}" {{ old('id_kategori', $data_buku->id_kategori) == $kat->id_kategori ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Cover -->
            <div class="md:col-span-2">
                <label class="block text-sm text-slate-300 mb-2">Cover Buku</label>
                @if($data_buku->cover_buku)
                    <img src="{{ asset('storage/' . $data_buku->cover_buku) }}" class="h-32 rounded-lg mb-4 border border-slate-600">
                @endif
                <input type="file" name="cover_buku" accept="image/*" class="w-full text-sm text-slate-400">
                <p class="text-xs text-slate-500 mt-1">Kosongkan jika tidak ingin mengganti cover.</p>
            </div>

             <!-- Sinopsis -->
             <div class="md:col-span-2">
                <label class="block text-sm text-slate-300 mb-2">Sinopsis</label>
                <textarea name="sinopsis" rows="4" class="w-full bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 text-white focus:border-blue-500 outline-none">{{ old('sinopsis', $data_buku->sinopsis) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-6 border-t border-slate-700">
            <a href="{{ route('petugas.buku.index') }}" class="px-6 py-3 rounded-xl border border-slate-600 text-slate-300 hover:bg-slate-800">Batal</a>
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30">Update Data</button>
        </div>
    </form>
</div>
@endsection
