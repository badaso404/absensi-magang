<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

/**
 * Sumber tunggal jadwal jam kerja magang.
 *
 * Sebelumnya jam masuk/pulang ditulis ulang di HomeController dan
 * AbsensiController dengan nilai berbeda (14:00 vs 15:00), jadi jadwal yang
 * ditampilkan ke user tidak sama dengan yang tersimpan di absensi.
 */
class JadwalKerja
{
    public function jamMasuk(?CarbonInterface $tanggal = null): Carbon
    {
        return $this->pukul(config('magang.jam_masuk'), $tanggal);
    }

    public function jamPulang(?CarbonInterface $tanggal = null): Carbon
    {
        $tanggal ??= Carbon::today();

        return $this->pukul(
            $tanggal->isFriday() ? config('magang.jam_pulang_jumat') : config('magang.jam_pulang'),
            $tanggal
        );
    }

    /**
     * Jam masuk/pulang sebagai string "HH:MM" untuk ditampilkan di view.
     *
     * @return array{masuk: string, pulang: string}
     */
    public function untukTampilan(?CarbonInterface $tanggal = null): array
    {
        return [
            'masuk'  => $this->jamMasuk($tanggal)->format('H:i'),
            'pulang' => $this->jamPulang($tanggal)->format('H:i'),
        ];
    }

    /**
     * Ubah "HH:MM" dari config menjadi Carbon pada tanggal yang diminta.
     */
    private function pukul(string $waktu, ?CarbonInterface $tanggal): Carbon
    {
        [$jam, $menit] = array_pad(explode(':', $waktu), 2, '0');

        return ($tanggal ? Carbon::parse($tanggal) : Carbon::today())
            ->copy()
            ->setTime((int) $jam, (int) $menit);
    }
}
