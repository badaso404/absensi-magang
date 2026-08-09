<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminCheck
{
    /**
     * Hanya meloloskan admin.
     *
     * Versi lama hanya menolak role Magang, sehingga role baru apa pun otomatis
     * mendapat akses admin. Sekarang aksesnya allowlist, bukan blocklist.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role_id != Role::Admin->value) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Akses ditolak.'], 403)
                : redirect()->route('home');
        }

        return $next($request);
    }
}
