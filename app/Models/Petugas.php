<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Petugas extends Model
{
    use SoftDeletes;

    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';

    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'alamat',
        'jenis_kelamin',
        'no_telepon',
        'email',
        'jabatan',
        'tanggal_bergabung',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Peminjaman
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_petugas', 'id_petugas');
    }

    /**
     * Accessor untuk nama agar kompatibel dengan pemanggilan ->name
     */
    public function getNameAttribute()
    {
        return $this->nama;
    }
}
