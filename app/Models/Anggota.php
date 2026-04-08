<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;
    protected $table = 'anggotas';

    /**
     * Primary key tabel.
     * Migration Anda menggunakan $table->id(), jadi primary key-nya 'id'.
     */
    protected $primaryKey = 'id';

    /**
     * Tipe data primary key.
     */
    protected $keyType = 'int';

    /**
     * Apakah primary key auto increment?
     */
    public $incrementing = true;

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment).
     * Sesuai dengan kolom di migration Anda.
     */
    protected $fillable = [
        'user_id',
        'nama',
        'nis_nisn',
        'kelas',
        'alamat',
        'jenis_kelamin',
        'no_telepon',
        'email',
        'tanggal_bergabung',
        'status',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'tanggal_bergabung' => 'date',
        // 'deleted_at' => 'datetime', // Jika pakai soft deletes
    ];

    /**
     * Relasi ke Model User.
     * Satu Anggota dimiliki oleh satu User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Peminjaman (Opsional, untuk masa depan).
     * Satu Anggota bisa punya banyak Peminjaman.
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_anggota'); // Asumsi FK di tabel peminjamans adalah id_anggota
    }
    
    /**
     * Scope untuk mencari anggota aktif saja (Opsional helper)
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}