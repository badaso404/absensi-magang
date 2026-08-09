<?php

namespace App\Http\Controllers;

use App\Enums\AbsensiStatus;
use App\Models\Absensi;
use App\Services\JadwalKerja;
use App\Services\ReverseGeocoder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public string $mainMenu = 'Absensi';

    public function __construct(
        private readonly JadwalKerja $jadwal,
        private readonly ReverseGeocoder $geocoder,
    ) {
    }

    public function index(): View
    {
        $userId = Auth::id();

        $absensi = Absensi::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit(7)
            ->get();

        $absensiHariIni = Absensi::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->first();

        $bulanIni = Absensi::where('user_id', $userId)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->get();

        return $this->createView('absensi.index', [
            'absensi'                => $absensi,
            'toggleAbsenPagi'        => !$absensiHariIni,
            'kalkulasiKeterlambatan' => Absensi::kalkulasiKeterlambatan($bulanIni),
            'absensiChart'           => Absensi::chartHarian($bulanIni),
        ]);
    }

    public function absen(Request $request, string $tipe): JsonResponse
    {
        if (!in_array($tipe, ['pagi', 'sore'], true)) {
            return response()->json(['success' => false, 'message' => 'Tipe absen tidak dikenal.'], 404);
        }

        $validated = $request->validate([
            'wfhwfo'    => $tipe === 'pagi' ? 'required|in:WFH,WFO,Dinas Luar' : 'nullable',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $userId = Auth::id();
        $now = Carbon::now();
        $jamMasuk = $this->jadwal->jamMasuk($now);
        $jamPulang = $this->jadwal->jamPulang($now);

        $absensi = Absensi::where('user_id', $userId)
            ->whereDate('created_at', $now->copy()->startOfDay())
            ->first();

        $latitude = $validated['latitude'] ?? null;
        $longitude = $validated['longitude'] ?? null;
        $lokasi = $this->geocoder->resolve($latitude, $longitude);

        if ($tipe === 'pagi') {
            if ($absensi) {
                return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absen pagi.'], 422);
            }

            Absensi::create([
                'user_id'            => $userId,
                'status'             => AbsensiStatus::Hadir,
                'schedule_in'        => $jamMasuk,
                'schedule_out'       => $jamPulang,
                'checked_in_at'      => $now,
                'checked_in_status'  => $now->greaterThan($jamMasuk)
                    ? AbsensiStatus::MasukTelat
                    : AbsensiStatus::TepatWaktu,
                'checked_out_status' => AbsensiStatus::BelumAbsen,
                'wfhwfo'             => $validated['wfhwfo'],
                'lokasi_user'        => $lokasi,
                'latitude'           => $latitude,
                'longitude'          => $longitude,
            ]);

            return response()->json([
                'success'  => true,
                'message'  => 'Absen pagi berhasil dicatat.',
                'location' => $lokasi,
            ]);
        }

        if (!$absensi) {
            return response()->json(['success' => false, 'message' => 'Data absen pagi tidak ditemukan!'], 404);
        }

        if ($absensi->checked_out_at) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan absen pulang.'], 422);
        }

        $absensi->update([
            'checked_out_at'     => $now,
            'schedule_out'       => $jamPulang,
            'checked_out_status' => $now->lessThan($jamPulang)
                ? AbsensiStatus::PulangCepat
                : AbsensiStatus::TepatWaktu,
            // Lokasi pulang hanya ditimpa kalau GPS memang terbaca, supaya
            // lokasi absen pagi tidak hilang saat user menolak izin lokasi.
            'lokasi_user'        => $lokasi ?? $absensi->lokasi_user,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Absen sore berhasil dicatat.',
            'location' => $lokasi ?? $absensi->lokasi_user,
        ]);
    }
}
