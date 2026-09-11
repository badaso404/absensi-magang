<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\UserSeksi;
use App\Http\Controllers\Concerns\FilterPeriode;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Daftar rekan magang: satu unit (Tim Saya) dan unit lain (Lintas Tim).
 *
 * Tujuannya perkenalan antar peserta, bukan administrasi, jadi yang
 * ditampilkan hanya data yang wajar dibagi ke sesama rekan: nama, foto,
 * asal/jurusan, periode magang, dan kontak yang memang diisi sendiri oleh
 * user di profilnya (WhatsApp, Instagram, LinkedIn).
 *
 * Data identitas dan administratif — alamat rumah, tanggal lahir, NISN/NIM,
 * email — sengaja tidak dibagikan. Itu urusan admin, bukan bahan berkenalan.
 * Aturan ini berlaku sama untuk Tim Saya maupun Lintas Tim.
 */
class TimController extends Controller
{
    use FilterPeriode;

    public string $mainMenu = 'Tim';

    /** Kolom yang boleh dibaca sesama rekan magang. */
    private const KOLOM_PUBLIK = [
        'id', 'name', 'avatar', 'seksi', 'asal', 'jurusan',
        'no_telp', 'instagram', 'linkedin',
        'tanggal_awal_magang', 'tanggal_akhir_magang',
    ];

    public function index(Request $request): View
    {
        $saya = Auth::user();

        // Tanpa seksi, tidak ada unit yang bisa ditampilkan sebagai "tim saya".
        $rekan = $saya->seksi
            ? $this->magangAktif()->where('seksi', $saya->seksi)->get()
            : collect();

        // Lintas tim: semua unit selain unit saya, atau satu unit tertentu
        // bila difilter. Unit sendiri tidak masuk sini karena sudah ada di
        // bagian atas — menampilkannya dua kali cuma bikin bingung.
        $seksiDipilih = $this->filterSeksi($request);

        if ($seksiDipilih && $saya->seksi && $seksiDipilih === $saya->seksi) {
            $seksiDipilih = null;
        }

        $lintas = $this->magangAktif()
            ->when(
                $seksiDipilih,
                fn ($q) => $q->where('seksi', $seksiDipilih),
                fn ($q) => $q->when($saya->seksi, fn ($q2) => $q2->where('seksi', '!=', $saya->seksi))
            )
            ->orderBy('seksi')
            ->orderBy('name')
            ->get();

        return $this->createView('tim.index', [
            'saya'         => $saya,
            'rekan'        => $rekan,
            'lintas'       => $lintas,
            'seksiDipilih' => $seksiDipilih,
            // Pilihan filter tidak menyertakan unit sendiri.
            'daftarSeksi'  => collect(UserSeksi::cases())
                ->reject(fn (UserSeksi $s) => $saya->seksi && $s === $saya->seksi)
                ->values(),
        ]);
    }

    /**
     * Query dasar: magang aktif selain diri sendiri, kolom publik saja.
     */
    private function magangAktif()
    {
        return User::select(self::KOLOM_PUBLIK)
            ->where('role_id', Role::Magang)
            ->whereKeyNot(Auth::id())
            ->aktifPada(Carbon::today())
            ->orderBy('name');
    }
}
