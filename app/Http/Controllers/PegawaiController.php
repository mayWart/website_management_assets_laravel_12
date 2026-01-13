<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    /**
     * Form isi pegawai (HANYA SEKALI)
     */
    public function create()
    {
        // ❌ Jika sudah pernah isi → dilarang
        if (Auth::user()->pegawai) {
            abort(403, 'Data pegawai sudah diisi');
        }

        return view('pegawai.create');
    }

    /**
     * Simpan data pegawai
     */
    public function store(Request $request)
    {
        // ❌ Proteksi ulang (double security)
        if (Auth::user()->pegawai) {
            abort(403, 'Tidak bisa mengisi ulang data pegawai');
        }

        $validated = $request->validate([
            'nip_pegawai' => 'required|unique:pegawai,nip_pegawai',
            'nama_pegawai' => 'required|string|max:255',
            'bidang_kerja' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
        ]);

        \Log::info('Creating pegawai data', [
            'user_id' => Auth::id(),
            'nip' => $validated['nip_pegawai'],
        ]);

        $pegawai = Pegawai::create([
            'nip_pegawai' => $validated['nip_pegawai'],
            'nama_pegawai' => $validated['nama_pegawai'],
            'bidang_kerja' => $validated['bidang_kerja'],
            'jabatan' => $validated['jabatan'],
            'status_pegawai' => 'aktif',
            'id_pengguna' => Auth::id(),
        ]);

        \Log::info('Pegawai created successfully', [
            'pegawai_id' => $pegawai->id_pegawai,
            'user_id' => Auth::id(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Data pegawai berhasil disimpan',
                'redirect' => route('dashboard'),
            ], 201);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Data pegawai berhasil disimpan');
    }
}
