<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',   // Pemilik room chat
        'sender_id', // Pengirim pesan
        'message',
        'is_read'
    ];

    /**
     * Relasi ke User (Pengirim Pesan)
     * Digunakan untuk mengambil username pengirim
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}