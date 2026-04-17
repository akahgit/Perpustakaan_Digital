<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        'harga_ganti',
        'stok',
        'stok_tersedia',
        'stok_rusak',
        'stok_hilang',
        'sinopsis',
        'cover_buku',
        'file_buku',
        'status',
    ];

    // Casting tipe data
    protected $casts = [
        'tahun_terbit' => 'integer',
        'harga_ganti' => 'decimal:2',
        'stok' => 'integer',
        'stok_tersedia' => 'integer',
        'stok_rusak' => 'integer',
        'stok_hilang' => 'integer',
        'is_active' => 'boolean',
    ];

    public static function generateUniqueSlug(string $judul, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($judul);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'buku';
        $keyName = (new static())->getKeyName();

        for ($counter = 1; ; $counter++) {
            $suffix = $counter === 1 ? '' : '-' . $counter;
            $slug = Str::substr($baseSlug, 0, 255 - strlen($suffix)) . $suffix;

            $exists = static::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId !== null, function ($query) use ($keyName, $ignoreId) {
                    $query->where($keyName, '!=', $ignoreId);
                })
                ->exists();

            if (! $exists) {
                return $slug;
            }
        }
    }

    // Relasi ke Kategori
    public function kategori()
    {
        // belongsTo(RelatedModel, ForeignKeyDiTabelIni, PrimaryKeyDiTabelRelated)
        return $this->belongsTo(KategoriBuku::class, 'id_kategori', 'id_kategori');
    }

    // Relasi ke Ulasan
    public function ulasans()
    {
        return $this->hasMany(UlasanBuku::class, 'id_buku', 'id_buku');
    }

    // Hitung rata-rata rating
    public function getAverageRatingAttribute()
    {
        return $this->ulasans()->avg('rating') ?: 0;
    }

    // Relasi ke Peminjaman
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku', 'id_buku');
    }
}
