<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BukuController extends Controller
{
    /**
     * Tampilkan Daftar Buku (Index)
     */
            public function index(Request $request)
    {
        $totalEksemplar = \App\Models\Buku::sum('stok') ?? 0;

        $judulBerbeda = \App\Models\Buku::count() ?? 0;

        $tersedia = \App\Models\Buku::sum('stok_tersedia') ?? 0;

        $sedangDipinjam = max(0, $totalEksemplar - $tersedia);

        $query = \App\Models\Buku::with('kategori');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('penerbit', 'like', "%{$search}%");
            });
        }

        if ($request->has('genre') && $request->genre != '') {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('slug', $request->genre);
            });
        }

        $bukus = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $kategoris = \App\Models\KategoriBuku::all();

        // Kirim variabel ke view dengan nama yang jelas
        return view('petugas.buku.index', compact(
            'bukus', 
            'kategoris', 
            'totalEksemplar', // Total Fisik
            'tersedia',       // Siap Pinjam
            'sedangDipinjam', // Dalam Peredaran
            'judulBerbeda'    // Jenis Koleksi
        ));
    }

    /**
     * Tampilkan Form Tambah (Create)
     */
    public function create()
    {
        $kategoris = KategoriBuku::all();
        return view('petugas.buku.create', compact('kategoris'));
    }

    /**
     * Simpan Data Baru (Store)
     */
    public function store(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'isbn' => 'nullable|string|max:20|unique:bukus,isbn',
            'stok' => 'required|integer|min:1',
            'id_kategori' => 'required|exists:kategori_bukus,id_kategori',
            'sinopsis' => 'nullable|string',
            'cover_buku' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle Upload Cover
        if ($request->hasFile('cover_buku')) {
            $validated['cover_buku'] = $request->file('cover_buku')->store('covers', 'public');
        }

        // Data Tambahan
        $validated['slug'] = Str::slug($validated['judul']);
        $validated['stok_tersedia'] = $validated['stok'];
        $validated['status'] = 'tersedia';

        // Simpan
        Buku::create($validated);

        return redirect()->route('petugas.buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    /**
     * Tampilkan Form Edit
     * Parameter wajib '$data_buku' sesuai route {data_buku}
     */
    public function edit(Buku $data_buku)
    {
        $kategoris = KategoriBuku::all();
        return view('petugas.buku.edit', compact('data_buku', 'kategoris'));
    }

    /**
     * Update Data
     */
        /**
     * Update Data Buku
     */
    public function update(Request $request, Buku $data_buku)
    {
        // 1. Validasi
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'isbn' => 'nullable|string|max:20|unique:bukus,isbn,' . $data_buku->id_buku . ',id_buku',
            'stok' => 'required|integer|min:0', // Pastikan minimal 0
            'id_kategori' => 'required|exists:kategori_bukus,id_kategori',
            'sinopsis' => 'nullable|string',
            'cover_buku' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Handle Upload Cover Baru
        if ($request->hasFile('cover_buku')) {
            if ($data_buku->cover_buku) {
                Storage::disk('public')->delete($data_buku->cover_buku);
            }
            $validated['cover_buku'] = $request->file('cover_buku')->store('covers', 'public');
        }
        
        $stokBaru = $validated['stok'];
        $validated['stok_tersedia'] = $stokBaru; // <--- TAMBAHKAN BARIS INI

        // Update status buku berdasarkan stok baru
        if ($stokBaru > 0) {
            $validated['status'] = 'tersedia';
        } else {
            $validated['status'] = 'habis';
        }

        // Tambahan data lain
        $validated['slug'] = Str::slug($validated['judul']);
        
        // 4. Eksekusi Update
        $data_buku->update($validated);

        return redirect()->route('petugas.buku.index')->with('success', 'Data buku berhasil diperbarui! Stok telah disinkronkan.');
    }

    /**
     * Hapus Data
     */
    public function destroy(Buku $data_buku)
    {
        if ($data_buku->cover_buku) {
            Storage::disk('public')->delete($data_buku->cover_buku);
        }
        $data_buku->delete();
        return redirect()->route('petugas.buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}