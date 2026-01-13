<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $fillable = [
        'nip_pegawai',
        'nama_pegawai',
        'bidang_kerja',
        'jabatan',
        'status_pegawai',
        'id_pengguna',
    ];

public function user()
{
    return $this->belongsTo(
        User::class,
        'id_pengguna',
        'id'
    );
}
}
