<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'dendas';

    /**
     * Primary key tabel
     */
    protected $primaryKey = 'id_denda';

    /**
     * Kolom yang boleh diisi massal
     */
    protected $fillable = [
        'id_peminjaman',
        'hari_terlambat',
        'denda_per_hari',
        'jumlah_denda',
        'status_pembayaran',
        'tanggal_bayar',
        'metode_pembayaran',
        'bukti_pembayaran',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'hari_terlambat' => 'integer',
        'denda_per_hari' => 'decimal:2',
        'jumlah_denda' => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    /**
     * Relasi ke Peminjaman
     * Satu denda dimiliki oleh satu transaksi peminjaman
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    /**
     * Scope: Ambil denda yang belum lunas
     */
    public function scopeBelumLunas($query)
    {
        return $query->where('status_pembayaran', 'belum_lunas');
    }

    /**
     * Scope: Ambil denda yang sudah lunas
     */
    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    /**
     * Helper: Format rupiah untuk tampilan
     */
    public function getFormattedJumlahDendaAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_denda, 0, ',', '.');
    }
    
    /**
     * Helper: Cek apakah denda ini masih aktif (belum dibayar)
     */
    public function isAktif()
    {
        return $this->status_pembayaran === 'belum_lunas';
    }
}