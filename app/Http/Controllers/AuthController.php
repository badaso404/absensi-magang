<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request; // Tambahkan ini
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function loginAttempt(LoginRequest $request)
    {
        $data = $request->validated();

        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']], true)) {
            return response()->json(['error' => 'Email atau password salah'], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'redirect' => route('home')
        ]);
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_src()]);
    }

    /**
     * PERBAIKAN: Tambahkan parameter Request agar session bisa dibersihkan total
     */
    public function logout(Request $request) 
    {
        // 1. Keluar dari guard Auth
        Auth::logout();

        // 2. Hancurkan semua data session user
        $request->session()->invalidate();

        // 3. Generate ulang token CSRF agar tidak bisa dipakai lagi (Keamanan)
        $request->session()->regenerateToken();

        // 4. Redirect ke halaman login
        return redirect()->route('login');
    }
}