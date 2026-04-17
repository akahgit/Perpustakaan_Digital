<?php

namespace App\Http\Controllers;

use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = KategoriBuku::withCount('bukus')
            ->orderBy('nama_kategori')
            ->paginate(15);

        $totalKategori = KategoriBuku::count();
        return view('petugas.kategori.index', compact('kategoris', 'totalKategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_bukus,nama_kategori',
            'deskripsi'     => 'nullable|string|max:500',
            'warna'         => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $validated['slug']      = Str::slug($validated['nama_kategori']);

        KategoriBuku::create($validated);

        return redirect()->route('petugas.kategori.index')
            ->with('success', 'Kategori "' . $validated['nama_kategori'] . '" berhasil ditambahkan!');
    }

    public function update(Request $request, KategoriBuku $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_bukus,nama_kategori,' . $kategori->id_kategori . ',id_kategori',
            'deskripsi'     => 'nullable|string|max:500',
            'warna'         => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $validated['slug']      = Str::slug($validated['nama_kategori']);

        $kategori->update($validated);

        return redirect()->route('petugas.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(KategoriBuku $kategori)
    {
        if ($kategori->bukus()->count() > 0) {
            return redirect()->route('petugas.kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $kategori->bukus()->count() . ' buku!');
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        return redirect()->route('petugas.kategori.index')
            ->with('success', 'Kategori "' . $nama . '" berhasil dihapus!');
    }
}
