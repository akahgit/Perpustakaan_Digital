<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $table      = 'dendas';
    protected $primaryKey = 'id_denda';

    protected $fillable = [
        'id_peminjaman',
        'jenis_denda',
        'hari_terlambat',
        'denda_per_hari',
        'jumlah_denda',
        'deskripsi',
        'status_pembayaran',
        'tanggal_bayar',
        'metode_pembayaran',
        'bukti_pembayaran',
        // Kolom baru pembayaran QRIS
        'bukti_foto',
        'status_verifikasi',
        'catatan_petugas',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'hari_terlambat'   => 'integer',
        'denda_per_hari'   => 'decimal:2',
        'jumlah_denda'     => 'decimal:2',
        'tanggal_bayar'    => 'date',
        'verified_at'      => 'datetime',
    ];

    /* ── Relasi ── */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /* ── Scopes ── */
    public function scopeBelumLunas($query)
    {
        return $query->where('status_pembayaran', 'belum_lunas');
    }

    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    public function scopeMenungguVerifikasi($query)
    {
        return $query->whereNotNull('bukti_foto')->where('status_verifikasi', 'pending');
    }

    /* ── Helpers ── */
    public function getFormattedJumlahDendaAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_denda, 0, ',', '.');
    }

    public function getLabelJenisDendaAttribute(): string
    {
        return match ($this->jenis_denda) {
            'kerusakan' => 'Kerusakan',
            'kehilangan' => 'Kehilangan',
            'gabungan' => 'Gabungan',
            default => 'Keterlambatan',
        };
    }

    public function isAktif(): bool
    {
        return $this->status_pembayaran === 'belum_lunas';
    }

    public function isMenungguVerifikasi(): bool
    {
        return $this->bukti_foto !== null && $this->status_verifikasi === 'pending';
    }
}
