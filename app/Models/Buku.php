<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buku extends Model
{
    use HasFactory, SoftDeletes;

    // Primary Key Custom
    protected $primaryKey = 'id_buku';

    // Field yang boleh diisi massal (Wajib sama persis dengan kolom di DB)
    protected $fillable = [
        'id_kategori',
        'isbn',
        'judul',
        'slug',
        'pengarang',
        'penerbit',
        'tahun_terbit',
        'stok',
        'stok_tersedia',
        'sinopsis',
        'cover_buku',
        'file_buku',
        'status',
    ];

    // Casting tipe data
    protected $casts = [
        'tahun_terbit' => 'integer',
        'stok' => 'integer',
        'stok_tersedia' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        // belongsTo(RelatedModel, ForeignKeyDiTabelIni, PrimaryKeyDiTabelRelated)
        return $this->belongsTo(KategoriBuku::class, 'id_kategori', 'id_kategori');
    }
}