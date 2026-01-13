<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePegawaiExists
{
    public function handle(Request $request, Closure $next)
    {
        // ⛔ JANGAN bypass auth di sini
        // auth sudah dijamin oleh route group

        $user = auth()->user();

        // Admin bebas
        if ($user->role === 'admin') {
            return $next($request);
        }

        // User wajib punya pegawai
        if (!$user->pegawai) {
            \Log::warning('User belum punya pegawai', [
                'user_id' => $user->id,
                'username' => $user->username,
            ]);
            return redirect()->route('pegawai.create');
        }

        \Log::info('User sudah punya pegawai', [
            'user_id' => $user->id,
            'pegawai_id' => $user->pegawai->id_pegawai,
        ]);

        return $next($request);
    }
}
