<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKegiatanExport;
use App\Http\Controllers\Concerns\FilterPeriode;
use App\Models\LaporanKegiatan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LaporanKegiatanController extends Controller
{
    use FilterPeriode;

    public string $mainMenu = 'laporankegiatan';

    private const BULAN = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    public function index(Request $request): View
    {
        ['month' => $month, 'year' => $year] = $this->filterPeriode($request);

        $laporan = LaporanKegiatan::where('user_id', Auth::id())
            ->when($month, fn ($q) => $q->whereMonth('tanggal', $month))
            ->when($year, fn ($q) => $q->whereYear('tanggal', $year))
            ->orderByDesc('tanggal')
            ->get();

        $years = LaporanKegiatan::selectRaw('YEAR(tanggal) as year')
            ->where('user_id', Auth::id())
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        return $this->createView('laporan-kegiatan.index', [
            'laporan'       => $laporan,
            'selectedMonth' => $month ?? '',
            'selectedYear'  => $year ?? '',
            'years'         => $years,
            'months'        => self::BULAN,
        ]);
    }

    public function create(): View
    {
        return $this->createView('laporan-kegiatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'         => 'required|date',
            'detail_kegiatan' => 'required|string',
            'lokasi'          => 'required|string',
            // Form create punya input file dokumentasi, tapi sebelumnya tidak
            // pernah divalidasi maupun disimpan — file yang diunggah user
            // hilang tanpa pesan apa pun.
            'dokumentasi'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        unset($validated['dokumentasi']);

        if ($request->hasFile('dokumentasi')) {
            $validated['dokumentasi'] = $this->simpanDokumentasi($request->file('dokumentasi'));
        }

        LaporanKegiatan::create($validated + [
            'user_id' => Auth::id(),
            'bulan'   => Carbon::parse($validated['tanggal'])->isoFormat('MMMM YYYY'),
        ]);

        return redirect()->route('laporan-kegiatan.index')
            ->with('success', 'Laporan kegiatan berhasil ditambahkan');
    }

    public function edit(LaporanKegiatan $laporanKegiatan): View
    {
        $this->pastikanMilikSendiri($laporanKegiatan);

        return $this->createView('laporan-kegiatan.edit', [
            'laporan' => $laporanKegiatan,
        ]);
    }

    public function update(Request $request, LaporanKegiatan $laporanKegiatan)
    {
        $this->pastikanMilikSendiri($laporanKegiatan);

        $validated = $request->validate([
            'tanggal'         => 'required|date',
            'detail_kegiatan' => 'required|string',
            'lokasi'          => 'required|string',
            'dokumentasi'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'tanggal'         => $validated['tanggal'],
            'detail_kegiatan' => $validated['detail_kegiatan'],
            'lokasi'          => $validated['lokasi'],
            'bulan'           => Carbon::parse($validated['tanggal'])->isoFormat('MMMM YYYY'),
        ];

        if ($request->hasFile('dokumentasi')) {
            $this->hapusDokumentasi($laporanKegiatan);
            $data['dokumentasi'] = $this->simpanDokumentasi($request->file('dokumentasi'));
        }

        $laporanKegiatan->update($data);

        return redirect()->route('laporan-kegiatan.index')
            ->with('success', 'Laporan kegiatan berhasil diperbarui');
    }

    public function destroy(LaporanKegiatan $laporanKegiatan)
    {
        $this->pastikanMilikSendiri($laporanKegiatan);

        $this->hapusDokumentasi($laporanKegiatan);
        $laporanKegiatan->delete();

        return redirect()->route('laporan-kegiatan.index')
            ->with('success', 'Laporan kegiatan berhasil dihapus');
    }

    public function export(Request $request): BinaryFileResponse
    {
        $filters = $this->filterPeriode($request);

        $fileName = 'laporan_kegiatan_' .
            ($filters['month'] ? $filters['month'] . '_' : '') .
            ($filters['year'] ?? date('Y')) . '.xlsx';

        return Excel::download(new LaporanKegiatanExport(Auth::id(), $filters), $fileName);
    }

    public function setting(): View
    {
        return $this->createView('laporan-kegiatan.setting');
    }

    public function settingUpdate(Request $request)
    {
        $validated = $request->validate([
            'pekerjaan'         => 'required|string|max:255',
            'bidang_suku_dinas' => 'required|string|max:255',
        ]);

        Auth::user()->update($validated);

        return redirect()->route('laporan-kegiatan.setting')
            ->with('success', 'Pengaturan laporan berhasil diperbarui');
    }

    private function pastikanMilikSendiri(LaporanKegiatan $laporan): void
    {
        abort_if($laporan->user_id !== Auth::id(), 403);
    }

    private function simpanDokumentasi($file): string
    {
        // Nama file di-generate sendiri agar tidak dikendalikan pengunggah.
        $fileName = now()->format('YmdHis') . '_' . str()->random(16) . '.' .
            strtolower($file->extension() ?: $file->getClientOriginalExtension());

        $file->move(public_path('storage/dokumentasi'), $fileName);

        return 'dokumentasi/' . $fileName;
    }

    private function hapusDokumentasi(LaporanKegiatan $laporan): void
    {
        if (!$laporan->dokumentasi) {
            return;
        }

        $path = public_path('storage/dokumentasi/' . basename($laporan->dokumentasi));

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
