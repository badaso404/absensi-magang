<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menutup fitur peserta magang (absensi) dari admin.
 *
 * Admin berperan sebagai pengawas, bukan peserta: dia bisa melihat dan
 * mengelola absensi semua orang, jadi kalau dia juga mengabsen dirinya sendiri
 * tidak ada pihak yang mengawasi catatannya.
 *
 * Menyembunyikan menu di navbar saja tidak cukup — URL-nya masih bisa diketik
 * langsung, jadi penolakannya dipasang di sini.
 */
class HanyaMagang
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role_id == Role::Admin->value) {
            return $request->expectsJson()
                ? response()->json([
                    'success' => false,
                    'message' => 'Admin tidak melakukan absensi. Fitur ini hanya untuk peserta magang.',
                ], 403)
                : redirect()->route('home');
        }

        return $next($request);
    }
}
