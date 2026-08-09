<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Queries\DaftarUser;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminAbsensiController extends Controller
{
    public string $mainMenu = "Admin Absensi";

    /**
     * Halaman ini menampilkan daftar magang beserta status absen hari yang
     * dipilih, dari situ admin membuka rekap per orang. Sebelumnya controller
     * hanya mengirim $absensi sementara viewnya membutuhkan daftar user dan
     * statistik, sehingga halaman selalu error "Undefined variable $totalUsers".
     */
    public function index(Request $request): View
    {
        $tanggal = $request->query('tanggal')
            ? Carbon::parse($request->query('tanggal'))
            : Carbon::today();

        return $this->createView('admin.absensi.index', DaftarUser::untukRequest($request) + [
            'tanggal' => $tanggal,
            'absensi' => Absensi::with('user')
                ->whereDate('created_at', $tanggal)
                ->get(),
        ]);
    }
}
