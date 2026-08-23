<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menendang user magang yang masa magangnya sudah lewat.
 *
 * Pengecekan di halaman login saja tidak cukup: sesi (dan cookie remember me)
 * yang dibuat sebelum tanggal akhir magang tetap hidup sampai kedaluwarsa,
 * jadi status aktif harus diperiksa ulang di setiap request.
 *
 * Admin tidak pernah diblokir supaya panel tetap bisa dibuka.
 */
class MagangAktif
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role_id == Role::Magang->value && !$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $pesan = 'Masa magang Anda sudah berakhir. Hubungi admin bila ini keliru.';

            return $request->expectsJson()
                ? response()->json(['error' => $pesan], 403)
                : redirect()->route('login')->withErrors(['email' => $pesan]);
        }

        return $next($request);
    }
}
