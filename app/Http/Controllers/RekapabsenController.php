<?php

namespace App\Http\Controllers;

use App\Exports\RekapAbsensiExport;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RekapabsenController extends Controller
{
    public string $mainMenu = 'rekapabsen';

    private const BULAN = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    /**
     * $user hanya terisi lewat route admin (/rekapabsen/{user}); user biasa
     * selalu melihat rekapnya sendiri.
     */
    public function index(Request $request, ?User $user = null): View
    {
        $userId = $user?->id ?? Auth::id();

        $absensi = $this->filter(Absensi::where('user_id', $userId), $request)
            ->orderByDesc('created_at')
            ->get();

        $years = Absensi::selectRaw('YEAR(created_at) as year')
            ->where('user_id', $userId)
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        return $this->createView('rekap.index', [
            'rekap'         => $absensi,
            'rekapUser'     => $user,
            'selectedMonth' => $request->input('month', ''),
            'selectedYear'  => $request->input('year', ''),
            'years'         => $years,
            'months'        => self::BULAN,
        ]);
    }

    public function export(Request $request, ?User $user = null): BinaryFileResponse
    {
        $target = $user ?? Auth::user();

        $filters = [
            'month' => $request->input('month'),
            'year'  => $request->input('year'),
        ];

        $fileName = sprintf(
            'rekap_absensi_%s.xlsx',
            str($target->name)->slug('_')->value() ?: $target->id
        );

        return Excel::download(new RekapAbsensiExport($target->id, $filters), $fileName);
    }

    private function filter($query, Request $request)
    {
        return $query
            ->when($request->filled('month'), fn ($q) => $q->whereMonth('created_at', $request->month))
            ->when($request->filled('year'), fn ($q) => $q->whereYear('created_at', $request->year));
    }
}
