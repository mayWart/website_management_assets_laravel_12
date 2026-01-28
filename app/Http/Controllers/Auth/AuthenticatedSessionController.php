<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        Log::info('Login request received', [
            'username' => $request->input('username'),
            'ip' => $request->ip(),
        ]);

        try {
            $request->authenticate();
            Log::info('Authentication passed for: ' . $request->input('username'));
        } catch (\Exception $e) {
            Log::error('Authentication failed', [
                'username' => $request->input('username'),
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        $request->session()->regenerate();

        $user = Auth::user();

        Log::info('Login successful', [
            'user_id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'has_pegawai' => $user->pegawai ? 'yes' : 'no',
        ]);

        // Always return JSON if Accept header is application/json OR X-Requested-With is XMLHttpRequest
        $shouldReturnJson = $request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest';

        if ($shouldReturnJson) {

            if ($user->role === 'admin') {
                return response()->json([
                    'message' => 'Login berhasil',
                    'redirect' => route('admin.dashboard'),
                ], 200);
            }

            if (!$user->pegawai) {
                return response()->json([
                    'message' => 'Lengkapi data pegawai',
                    'redirect' => route('pegawai.create'),
                ], 200);
            }

            return response()->json([
                'message' => 'Login berhasil',
                'redirect' => route('dashboard'),
            ], 200);
        }

        // fallback non-AJAX
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if (!$user->pegawai) {
            return redirect()->route('pegawai.create');
        }

        return redirect()->route('dashboard');
    }


    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Logout tidak perlu SweetAlert
        return redirect()->route('login');
    }
}
