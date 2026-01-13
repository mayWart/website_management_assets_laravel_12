<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaiTable extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->string('nip_pegawai')->unique();
            $table->string('nama_pegawai');
            $table->string('bidang_kerja');
            $table->string('jabatan');
            $table->enum('status_pegawai', ['aktif', 'nonaktif'])->default('aktif');

            $table->foreignId('id_pengguna')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
}
