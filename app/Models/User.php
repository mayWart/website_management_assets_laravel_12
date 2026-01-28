<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Pegawai;
use App\Models\Message; // Import Model Message

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * ================================
     * HELPER ROLE (UNTUK CHAT)
     * ================================
     */
    public function isAdmin()
    {
        // Pastikan value 'admin' sesuai dengan isi kolom role di database Anda
        // Jika di database tulisannya 'administrator', ganti jadi 'administrator'
        return $this->role === 'admin';
    }

    /**
     * ================================
     * RELASI KE CHAT MESSAGE
     * ================================
     */
    public function chats()
    {
        // Relasi ke pesan di mana User ini adalah pemilik percakapan (user_id)
        return $this->hasMany(Message::class, 'user_id');
    }

    /**
     * ================================
     * RELASI KE PEGAWAI
     * ================================
     */
    public function pegawai()
    {
        return $this->hasOne(
            Pegawai::class,
            'id_pengguna', 
            'id'
        );
    }

    /**
     * ================================
     * CEK APAKAH PEGAWAI AKTIF
     * ================================
     */
    public function isPegawaiAktif(): bool
    {
        // Jika user tidak punya data pegawai → dianggap TIDAK AKTIF
        if (!$this->pegawai) {
            return false;
        }

        // Cek status pegawai
        return $this->pegawai->status_pegawai === 'aktif';
    }
}