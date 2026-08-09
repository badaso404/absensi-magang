<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LaporanKegiatan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKegiatanExport;

class AdminLaporanController extends Controller
{
    // Akses admin sudah dijaga middleware AdminCheck di routes/web.php.
    public string $mainMenu = "laporanmagang";

    public function index(Request $request)
    {
        // Ambil semua user yang bukan admin (di kode anda admin = role_id == 1)
        $magangs = User::where('role_id', '!=', 1)
            ->orderBy('name')
            ->get();

        // untuk setiap user hitung jumlah laporan sesuai filter (jika ada)
        $users = $magangs->map(function ($u) use ($request) {
            $q = LaporanKegiatan::where('user_id', $u->id);

            if ($request->filled('month')) {
                $q->whereMonth('tanggal', $request->month);
            }

            if ($request->filled('year')) {
                $q->whereYear('tanggal', $request->year);
            }

            $u->laporan_count = $q->count();
            return $u;
        });

        $totalMagang = $magangs->count();
        $totalLaporan = LaporanKegiatan::when($request->filled('month'), function ($q) use ($request) {
                $q->whereMonth('tanggal', $request->month);
            })
            ->when($request->filled('year'), function ($q) use ($request) {
                $q->whereYear('tanggal', $request->year);
            })
            ->count();

        // years & months (dipakai di filter UI jika diinginkan)
        $years = LaporanKegiatan::selectRaw('YEAR(tanggal) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('admin.laporan.index', [
            'mainMenu' => $this->mainMenu,
            'users' => $users,
            'totalMagang' => $totalMagang,
            'totalLaporan' => $totalLaporan,
            'years' => $years,
            'months' => $months,
            'selectedMonth' => $request->month ?? '',
            'selectedYear' => $request->year ?? '',
        ]);
    }

    /**
     * Tampilkan daftar laporan untuk satu user magang.
     */
    public function show(User $user, Request $request)
    {
        // pastikan user adalah magang
     

        $query = LaporanKegiatan::where('user_id', $user->id);

        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        $laporan = $query->orderByDesc('tanggal')->get();

        return view('admin.laporan.user', [
            'mainMenu' => $this->mainMenu,
            'user' => $user,
            'laporan' => $laporan,
            'selectedMonth' => $request->month ?? '',
            'selectedYear' => $request->year ?? '',
        ]);
    }

    /**
     * Export laporan user magang (memakai LaporanKegiatanExport yang sudah ada).
     */
    public function export(User $user, Request $request)
    {
      

        $filters = [
            'month' => $request->month,
            'year' => $request->year,
        ];

        $fileName = 'laporan_' . $user->id . '_' .
                    ($request->month ? $request->month . '_' : '') .
                    ($request->year ?? date('Y')) . '.xlsx';

        return Excel::download(new LaporanKegiatanExport($user->id, $filters), $fileName);
    }
}