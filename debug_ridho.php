<?php

// Load Laravel app
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Get user ridho
$user = \App\Models\User::where('username', 'ridho')->first();

echo "\n=== DEBUG USER RIDHO ===\n";
echo "User Found: " . ($user ? "YES" : "NO") . "\n";

if ($user) {
    echo "User ID: " . $user->id . "\n";
    echo "Username: " . $user->username . "\n";
    echo "Role: " . $user->role . "\n";
    echo "\nPegawai Data:\n";
    
    $pegawai = $user->pegawai;
    if ($pegawai) {
        echo "  - ID Pegawai: " . $pegawai->id_pegawai . "\n";
        echo "  - Nama: " . $pegawai->nama_pegawai . "\n";
        echo "  - NIP: " . $pegawai->nip_pegawai . "\n";
        echo "  - Bidang: " . $pegawai->bidang_kerja . "\n";
        echo "  - Jabatan: " . $pegawai->jabatan . "\n";
        echo "  - Status: " . $pegawai->status_pegawai . "\n";
        echo "  - ID Pengguna: " . $pegawai->id_pengguna . "\n";
    } else {
        echo "  NO PEGAWAI DATA\n";
    }
}

echo "\n";
