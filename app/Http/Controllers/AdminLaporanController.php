<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\UserSeksi;
use App\Http\Controllers\Concerns\FilterPeriode;
use App\Models\User;
use App\Models\LaporanKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKegiatanExport;

class AdminLaporanController extends Controller
{
    use FilterPeriode;

    // Akses admin sudah dijaga middleware AdminCheck di routes/web.php.
    public string $mainMenu = "laporanmagang";

    public function index(Request $request)
    {
        ['month' => $month, 'year' => $year] = $this->filterPeriode($request);
        $seksi = $this->filterSeksi($request);

        // Daftar mengikuti periode yang difilter: memfilter ke Juni menampilkan
        // magang yang aktif pada Juni, bukan angkatan hari ini. Tanpa filter,
        // yang tampil adalah yang masa magangnya sedang berjalan.
        [$mulai, $selesai] = $this->rentangPeriode($month, $year);

        $magangs = User::where('role_id', Role::Magang)
            ->aktifDalamRentang($mulai, $selesai)
            ->when($seksi, fn ($q) => $q->where('seksi', $seksi))
            ->orderBy('name')
            ->get();

        // untuk setiap user hitung jumlah laporan sesuai filter (jika ada)
        $users = $magangs->map(function ($u) use ($month, $year) {
            $u->laporan_count = LaporanKegiatan::where('user_id', $u->id)
                ->when($month, fn ($q) => $q->whereMonth('tanggal', $month))
                ->when($year, fn ($q) => $q->whereYear('tanggal', $year))
                ->count();

            return $u;
        });

        $totalMagang = $magangs->count();

        // Total laporan ikut dibatasi seksi yang dipilih, kalau tidak angka
        // ringkasan akan bertentangan dengan daftar di bawahnya.
        $totalLaporan = LaporanKegiatan::whereIn('user_id', $magangs->pluck('id'))
            ->when($month, fn ($q) => $q->whereMonth('tanggal', $month))
            ->when($year, fn ($q) => $q->whereYear('tanggal', $year))
            ->count();

        // years & months (dipakai di filter UI jika diinginkan)
        $years = LaporanKegiatan::selectRaw('YEAR(tanggal) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        return view('admin.laporan.index', [
            'mainMenu' => $this->mainMenu,
            'users' => $users,
            'totalMagang' => $totalMagang,
            'totalLaporan' => $totalLaporan,
            'years' => $years,
            'months' => $this->namaBulan(),
            'selectedMonth' => $month ?? '',
            'selectedYear' => $year ?? '',
            'selectedSeksi' => $seksi ?? 'all',
            'seksiList' => UserSeksi::cases(),
        ]);
    }

    /**
     * Tampilkan daftar laporan untuk satu user magang.
     */
    public function show(User $user, Request $request)
    {
        // Default ke bulan berjalan: yang paling sering dicek pembimbing adalah
        // kegiatan bulan ini, bukan seluruh riwayat sejak awal magang.
        // ?month=all menampilkan semuanya.
        ['month' => $month, 'year' => $year] = $this->periodeLaporan($request);

        $laporan = LaporanKegiatan::where('user_id', $user->id)
            ->when($month, fn ($q) => $q->whereMonth('tanggal', $month))
            ->when($year, fn ($q) => $q->whereYear('tanggal', $year))
            ->orderByDesc('tanggal')
            ->get();

        // Tahun yang benar-benar punya laporan, untuk isi dropdown.
        $years = LaporanKegiatan::where('user_id', $user->id)
            ->selectRaw('YEAR(tanggal) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        return view('admin.laporan.user', [
            'mainMenu' => $this->mainMenu,
            'user' => $user,
            'laporan' => $laporan,
            'selectedMonth' => $month ?? 'all',
            'selectedYear' => $year ?? 'all',
            'months' => $this->namaBulan(),
            'years' => $years ?: [Carbon::today()->year],
        ]);
    }

    /**
     * Ubah filter bulan/tahun menjadi rentang tanggal yang harus dicakup.
     *
     * - bulan + tahun : satu bulan itu
     * - tahun saja    : satu tahun penuh
     * - bulan saja    : bulan tersebut pada tahun berjalan
     * - tanpa filter  : hari ini (siapa yang magang sekarang)
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function rentangPeriode(?int $month, ?int $year): array
    {
        if (!$month && !$year) {
            $hariIni = Carbon::today();

            return [$hariIni, $hariIni];
        }

        if ($month) {
            $awal = Carbon::create($year ?? Carbon::today()->year, $month, 1)->startOfMonth();

            return [$awal, $awal->copy()->endOfMonth()];
        }

        $awal = Carbon::create($year, 1, 1)->startOfYear();

        return [$awal, $awal->copy()->endOfYear()];
    }

    /**
     * Periode untuk halaman detail: bulan berjalan bila tidak diminta lain.
     *
     * @return array{month: int|null, year: int|null}
     */
    private function periodeLaporan(Request $request): array
    {
        $semua = $request->query('month') === 'all';

        if ($semua) {
            return ['month' => null, 'year' => null];
        }

        ['month' => $month, 'year' => $year] = $this->filterPeriode($request);

        return [
            'month' => $month ?? Carbon::today()->month,
            'year'  => $year ?? Carbon::today()->year,
        ];
    }

    /**
     * @return array<int, string>
     */
    private function namaBulan(): array
    {
        return [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    }

    /**
     * Export laporan user magang (memakai LaporanKegiatanExport yang sudah ada).
     */
    public function export(User $user, Request $request)
    {
        $filters = $this->periodeLaporan($request);

        $fileName = 'laporan_' . $user->id . '_' .
                    ($filters['month'] ? $filters['month'] . '_' : '') .
                    ($filters['year'] ?? date('Y')) . '.xlsx';

        return Excel::download(new LaporanKegiatanExport($user->id, $filters), $fileName);
    }
}