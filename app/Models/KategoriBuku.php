<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBuku extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
        'warna',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** Relasi: satu kategori punya banyak buku */
    public function bukus()
    {
        return $this->hasMany(Buku::class, 'id_kategori', 'id_kategori');
    }

    /** Scope: hanya yang aktif */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}