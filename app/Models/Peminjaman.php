<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';
    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'id_anggota',
        'id_buku',
        'id_petugas',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_realisasi',
        'durasi_pinjam',
        'status_peminjaman',
        'kondisi_pengembalian',
        'catatan_kondisi',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_realisasi' => 'date',
        'durasi_pinjam' => 'integer',
        'id_petugas' => 'integer', // Casting ke integer agar handle null dengan baik
    ];

    // Relasi ke Anggota
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }

    // Relasi ke Buku
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    // Relasi ke Petugas
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    // Scope: Ambil yang sedang aktif (dipinjam, terlambat, atau menunggu konfirmasi)
    public function scopeAktif($query)
    {
        return $query->whereIn('status_peminjaman', ['dipinjam', 'terlambat', 'menunggu_konfirmasi']);
    }

    // Helper: Cek keterlambatan
    public function isTerlambat()
    {
        if ($this->status_peminjaman === 'dikembalikan') return false;
        return $this->tanggal_kembali_rencana < Carbon::today();
    }

     public function denda()
    {
        return $this->hasMany(Denda::class, 'id_peminjaman');
    }
}
