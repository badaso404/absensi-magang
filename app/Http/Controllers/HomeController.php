<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
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
