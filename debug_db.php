<?php
// Load Laravel
$app = require __DIR__ . '/bootstrap/app.php';

// Get database connection
$db = $app->make('Illuminate\Database\DatabaseManager');

// Check users table
echo "=== USERS IN DATABASE ===\n";
$users = $db->table('users')->get();
foreach ($users as $user) {
    echo "ID: {$user->id}, Username: {$user->username}, Role: {$user->role}\n";
}

// Check pegawai table
echo "\n=== PEGAWAI IN DATABASE ===\n";
$pegawai = $db->table('pegawai')->get();
foreach ($pegawai as $p) {
    echo "ID: {$p->id}, ID Pengguna: {$p->id_pengguna}, NIP: {$p->nip_pegawai}, Name: {$p->nama_pegawai}\n";
}

// Check sessions
echo "\n=== SESSIONS IN DATABASE ===\n";
$sessions = $db->table('sessions')->get();
echo "Total sessions: " . count($sessions) . "\n";
foreach ($sessions as $session) {
    echo "Session ID: {$session->id}\n";
}
