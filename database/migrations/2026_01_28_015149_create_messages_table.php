<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */


public function up()
{
    Schema::create('messages', function (Blueprint $table) {
        $table->id();
        
        // user_id: Menandakan "Room Chat" ini milik siapa.
        // (Misal: User A chat, maka user_id = ID User A. Admin balas, user_id tetap ID User A)
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        // sender_id: Siapa yang mengetik pesan ini (Bisa User A, Bisa Admin)
        $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
        
        $table->text('message');
        $table->boolean('is_read')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
