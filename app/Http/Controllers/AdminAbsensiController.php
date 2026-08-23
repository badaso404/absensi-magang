<?php

namespace App\Http\Controllers;

use App\Enums\AbsensiStatus;
use App\Enums\Role;
use App\Enums\UserSeksi;
use App\Http\Controllers\Concerns\FilterPeriode;
use App\Models\Absensi;
use App\Models\User;
use App\Services\JadwalKerja;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminAbsensiController extends Controller
{
    use FilterPeriode;

    public string $mainMenu = "Admin Absensi";

    public function __construct(private readonly JadwalKerja $jadwal)
    {
    }

    /**
     * Papan pantau absensi harian.
     *
     * Sumbu halaman ini adalah tanggal, bukan daftar orang: admin memilih satu
     * hari lalu melihat seluruh magang aktif beserta status absennya hari itu.
     *
     * Magang yang belum absen tetap muncul sebagai baris tersendiri — kalau
     * halaman ini hanya menampilkan tabel absensi, justru orang yang bolong
     * (yang paling perlu ditindak) tidak kelihatan sama sekali.
     */
    public function index(Request $request): View
    {
        $tanggal = $this->tanggalDariRequest($request->query('tanggal'));
        $seksi = $this->filterSeksi($request);

        // aktifPada() — bukan active() — supaya daftarnya mengikuti tanggal
        // yang sedang dibuka: yang belum mulai magang tidak dihitung bolos,
        // dan yang sudah selesai tetap muncul di tanggal saat dia masih aktif.
        $magang = User::where('role_id', Role::Magang)
            ->aktifPada($tanggal)
            ->when($seksi, fn ($q) => $q->where('seksi', $seksi))
            ->orderBy('name')
            ->get();

        $absensi = Absensi::whereDate('created_at', $tanggal)
            ->whereIn('user_id', $magang->pluck('id'))
            ->get()
            ->keyBy('user_id');

        // Satu baris per magang; absensinya null kalau hari itu tidak absen.
        $baris = $magang->map(fn (User $u) => [
            'user'    => $u,
            'absensi' => $absensi->get($u->id),
        ]);

        $hadir = $absensi->count();
        $hariKerja = $this->jadwal->hariKerja($tanggal);

        return $this->createView('admin.absensi.index', [
            'tanggal'       => $tanggal,
            'hariKerja'     => $hariKerja,
            'baris'         => $baris,
            'totalMagang'   => $magang->count(),
            'hadir'         => $hadir,
            // Di akhir pekan tidak ada kewajiban absen, jadi tidak ada yang bolos.
            'belumAbsen'    => $hariKerja ? $magang->count() - $hadir : 0,
            'telat'         => $absensi->where('checked_in_status', AbsensiStatus::MasukTelat)->count(),
            'belumPulang'   => $absensi->whereNull('checked_out_at')->count(),
            'selectedSeksi' => $seksi,
            'seksiList'     => UserSeksi::cases(),
        ]);
    }

    /**
     * Carbon::parse() melempar exception untuk nilai sembarang seperti
     * ?tanggal=xyz atau ?tanggal[]=..., jadi tanggal tak terbaca dijatuhkan
     * ke hari ini alih-alih membuat halaman balas HTTP 500.
     */
    private function tanggalDariRequest(mixed $nilai): Carbon
    {
        if (!is_string($nilai) || $nilai === '') {
            return Carbon::today();
        }

        try {
            return Carbon::parse($nilai)->startOfDay();
        } catch (\Throwable) {
            return Carbon::today();
        }
    }
}
