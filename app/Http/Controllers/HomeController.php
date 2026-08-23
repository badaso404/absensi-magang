<?php

namespace App\Http\Controllers;

use App\Enums\AbsensiStatus;
use App\Enums\Role;
use App\Models\Absensi;
use App\Models\User;
use App\Services\JadwalKerja;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public string $mainMenu = 'Home';

    public function __construct(private readonly JadwalKerja $jadwal)
    {
    }

    public function index(): View
    {
        // Admin tidak mengisi absensi, jadi dashboard "absensi saya" kosong
        // baginya. Dia dapat ringkasan pemantauan sebagai gantinya.
        if (Auth::user()->role_id == Role::Admin->value) {
            return $this->berandaAdmin();
        }

        $userId = Auth::id();
        $hariIni = Carbon::today();

        $terbaru = Absensi::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit(7)
            ->get();

        $bulanIni = Absensi::where('user_id', $userId)
            ->whereYear('created_at', $hariIni->year)
            ->whereMonth('created_at', $hariIni->month)
            ->get();

        $jadwal = $this->jadwal->untukTampilan($hariIni);

        return $this->createView('beranda.index', [
            'absensi'                => $terbaru,
            'kalkulasiKeterlambatan' => Absensi::kalkulasiKeterlambatan($bulanIni),
            'absensiChart'           => Absensi::chartHarian($bulanIni),
            'schedule_in'            => $jadwal['masuk'],
            'schedule_out'           => $jadwal['pulang'],
            'toggleAbsenPagi'        => $this->tahapAbsenHariIni($bulanIni, $hariIni),
        ]);
    }

    /**
     * Ringkasan pemantauan absensi magang untuk admin.
     */
    private function berandaAdmin(): View
    {
        $hariIni = Carbon::today();

        // Konsisten dengan papan pantau: yang belum mulai magang belum
        // terhitung, meski tanggal akhirnya masih jauh.
        $magangAktif = User::where('role_id', Role::Magang)->aktifPada($hariIni)->get();

        $absensiHariIni = Absensi::with('user')
            ->whereDate('created_at', $hariIni)
            ->whereIn('user_id', $magangAktif->pluck('id'))
            ->get();

        $sudahAbsen = $absensiHariIni->pluck('user_id')->unique();
        $hariKerja = $this->jadwal->hariKerja($hariIni);

        return $this->createView('beranda.admin', [
            'tanggal'        => $hariIni,
            'hariKerja'      => $hariKerja,
            'totalMagang'    => $magangAktif->count(),
            'sudahAbsen'     => $sudahAbsen->count(),
            // Akhir pekan bukan hari absen, jadi tidak ada yang dianggap bolos.
            'belumAbsen'     => $hariKerja ? $magangAktif->count() - $sudahAbsen->count() : 0,
            'telat'          => $absensiHariIni
                ->where('checked_in_status', AbsensiStatus::MasukTelat)
                ->count(),
            'belumPulang'    => $absensiHariIni->whereNull('checked_out_at')->count(),
            'absensiHariIni' => $absensiHariIni->sortByDesc('checked_in_at'),
            'daftarBelum'    => $hariKerja
                ? $magangAktif->whereNotIn('id', $sudahAbsen)->sortBy('name')
                : $magangAktif->take(0),
        ]);
    }

    /**
     * Menentukan tombol absen mana yang muncul: 'pagi', 'sore', atau 'selesai'.
     */
    private function tahapAbsenHariIni($absensi, Carbon $hariIni): string
    {
        $absenHariIni = $absensi->first(
            fn (Absensi $a) => $a->created_at->isSameDay($hariIni)
        );

        if (!$absenHariIni) {
            return 'pagi';
        }

        return $absenHariIni->checked_out_at ? 'selesai' : 'sore';
    }
}
