<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Daftar rekan magang satu unit/seksi.
 *
 * Tujuannya perkenalan antar peserta, bukan administrasi, jadi yang
 * ditampilkan hanya data yang wajar dibagi ke sesama rekan: nama, foto,
 * asal/jurusan, periode magang, dan kontak yang memang diisi sendiri oleh
 * user di profilnya (WhatsApp, Instagram, LinkedIn).
 *
 * Data identitas dan administratif — alamat rumah, tanggal lahir, NISN/NIM,
 * email — sengaja tidak dibagikan. Itu urusan admin, bukan bahan berkenalan.
 */
class TimController extends Controller
{
    public string $mainMenu = 'Tim';

    /** Kolom yang boleh dibaca rekan satu unit. */
    private const KOLOM_PUBLIK = [
        'id', 'name', 'avatar', 'seksi', 'asal', 'jurusan',
        'no_telp', 'instagram', 'linkedin',
        'tanggal_awal_magang', 'tanggal_akhir_magang',
    ];

    public function index(): View
    {
        $saya = Auth::user();

        // Tanpa seksi, tidak ada unit yang bisa ditampilkan.
        $rekan = $saya->seksi
            ? User::select(self::KOLOM_PUBLIK)
                ->where('role_id', Role::Magang)
                ->where('seksi', $saya->seksi)
                ->whereKeyNot($saya->id)
                ->aktifPada(Carbon::today())
                ->orderBy('name')
                ->get()
            : collect();

        return $this->createView('tim.index', [
            'saya'  => $saya,
            'rekan' => $rekan,
        ]);
    }
}
