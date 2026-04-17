<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BukuSlugTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_generates_a_unique_slug_for_duplicate_titles(): void
    {
        $this->actingAs($this->makePetugas());
        $kategori = $this->makeKategori();

        Buku::create([
            'judul' => 'Laskar Pelangi',
            'slug' => 'laskar-pelangi',
            'pengarang' => 'Andrea Hirata',
            'penerbit' => 'Bentang',
            'tahun_terbit' => 2005,
            'stok' => 3,
            'stok_tersedia' => 3,
            'id_kategori' => $kategori->id_kategori,
            'status' => 'tersedia',
        ]);

        $response = $this->post(route('petugas.buku.store'), [
            'judul' => 'Laskar Pelangi',
            'pengarang' => 'Muhamad',
            'penerbit' => 'Dimas',
            'tahun_terbit' => 2026,
            'stok' => 1,
            'id_kategori' => $kategori->id_kategori,
            'sinopsis' => 'Tes slug unik',
        ]);

        $response->assertRedirect(route('petugas.buku.index'));
        $this->assertDatabaseHas('bukus', [
            'judul' => 'Laskar Pelangi',
            'slug' => 'laskar-pelangi-2',
        ]);
        $this->assertDatabaseCount('bukus', 2);
    }

    public function test_update_keeps_the_same_slug_for_the_same_book_title(): void
    {
        $this->actingAs($this->makePetugas());
        $kategori = $this->makeKategori();

        $buku = Buku::create([
            'judul' => 'Laskar Pelangi',
            'slug' => 'laskar-pelangi',
            'pengarang' => 'Andrea Hirata',
            'penerbit' => 'Bentang',
            'tahun_terbit' => 2005,
            'stok' => 3,
            'stok_tersedia' => 3,
            'id_kategori' => $kategori->id_kategori,
            'status' => 'tersedia',
        ]);

        $response = $this->put(route('petugas.buku.update', $buku->id_buku), [
            'judul' => 'Laskar Pelangi',
            'pengarang' => 'Andrea Hirata',
            'penerbit' => 'Bentang',
            'tahun_terbit' => 2005,
            'stok' => 3,
            'id_kategori' => $kategori->id_kategori,
            'sinopsis' => 'Tetap sama',
        ]);

        $response->assertRedirect(route('petugas.buku.index'));

        $buku->refresh();

        $this->assertSame('laskar-pelangi', $buku->slug);
    }

    public function test_update_generates_a_unique_slug_when_using_another_books_title(): void
    {
        $this->actingAs($this->makePetugas());
        $kategori = $this->makeKategori();

        Buku::create([
            'judul' => 'Laskar Pelangi',
            'slug' => 'laskar-pelangi',
            'pengarang' => 'Andrea Hirata',
            'penerbit' => 'Bentang',
            'tahun_terbit' => 2005,
            'stok' => 3,
            'stok_tersedia' => 3,
            'id_kategori' => $kategori->id_kategori,
            'status' => 'tersedia',
        ]);

        $bukuLain = Buku::create([
            'judul' => 'Bumi Manusia',
            'slug' => 'bumi-manusia',
            'pengarang' => 'Pramoedya',
            'penerbit' => 'Hasta',
            'tahun_terbit' => 1980,
            'stok' => 2,
            'stok_tersedia' => 2,
            'id_kategori' => $kategori->id_kategori,
            'status' => 'tersedia',
        ]);

        $response = $this->put(route('petugas.buku.update', $bukuLain->id_buku), [
            'judul' => 'Laskar Pelangi',
            'pengarang' => 'Pramoedya',
            'penerbit' => 'Hasta',
            'tahun_terbit' => 1980,
            'stok' => 2,
            'id_kategori' => $kategori->id_kategori,
            'sinopsis' => 'Ganti judul',
        ]);

        $response->assertRedirect(route('petugas.buku.index'));

        $bukuLain->refresh();

        $this->assertSame('laskar-pelangi-2', $bukuLain->slug);
    }

    private function makePetugas(): User
    {
        return User::create([
            'username' => 'petugas-test',
            'name' => 'Petugas Test',
            'email' => 'petugas@example.com',
            'password' => 'password',
            'role' => 'petugas',
            'transaction_pin' => '1234',
        ]);
    }

    private function makeKategori(): KategoriBuku
    {
        return KategoriBuku::create([
            'nama_kategori' => 'Novel',
            'slug' => 'novel',
            'deskripsi' => 'Kategori novel',
            'warna' => '#3B82F6',
            'is_active' => true,
        ]);
    }
}
