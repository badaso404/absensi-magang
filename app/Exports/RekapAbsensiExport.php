<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapAbsensiExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $userId;
    protected $filters;

    public function __construct($userId, $filters = [])
    {
        $this->userId = $userId;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Absensi::with('user')->where('user_id', $this->userId);

        if (!empty($this->filters['month'])) {
            $query->whereMonth('created_at', $this->filters['month']);
        }

        if (!empty($this->filters['year'])) {
            $query->whereYear('created_at', $this->filters['year']);
        }

        return $query->orderByDesc('created_at')->get()->map(function ($item) {
            $dayName = $item->created_at?->translatedFormat('l') ?? '-';
            $isWeekend = in_array($dayName, ['Sabtu', 'Minggu']);

            return [
                'Hari'          => $dayName,
                'Tanggal'       => $item->created_at?->format('d') ?? '-',
                'Bulan'         => $item->created_at?->translatedFormat('F') ?? '-',
                'Tahun'         => $item->created_at?->format('Y') ?? '-',
                'Nama User'     => $item->user->name ?? '-',
                'Jam Masuk'     => $item->checked_in_at?->format('H:i:s') ?? '-',
                'Status Masuk'  => $item->checked_in_status?->text() ?? 'Belum Absen',
                'Jam Keluar'    => $item->checked_out_at?->format('H:i:s') ?? '-',
                'Status Pulang' => $item->checked_out_status?->text() ?? 'Belum Absen',
                'Status Hari'   => $item->status?->text() ?? '-',
                'Mode Kerja'    => $item->wfhwfo ?? '-',
                'Lokasi Absen'  => $item->lokasi_user ?? 'Lokasi tidak tersedia',
                'Latitude'      => $item->latitude ?? '-',
                'Longitude'     => $item->longitude ?? '-',
                'Keterangan'    => $isWeekend ? 'Hari Libur' : 'Hari Kerja',
            ];
        });
    }

    public function headings(): array
    {
        $userName = User::find($this->userId)->name ?? '-';

        return [
            ["Data Rekap Absensi User: {$userName}"], // baris judul
            [], // baris kosong
            [
                'Hari',
                'Tanggal',
                'Bulan',
                'Tahun',
                'Nama User',
                'Jam Masuk',
                'Status Masuk',
                'Jam Keluar',
                'Status Pulang',
                'Status Hari',
                'Mode Kerja',
                'Lokasi Absen',
                'Latitude',
                'Longitude',
                'Keterangan',
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge A1 sampai O1 (sesuaikan dengan jumlah kolom baru)
        $sheet->mergeCells('A1:O2');

        // Center align A1:O2
        $sheet->getStyle('A1:O2')->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');

        // Bold judul
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Bold heading kolom
        $sheet->getStyle('A3:O3')->getFont()->setBold(true);

        // Background color untuk header
        $sheet->getStyle('A3:O3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4CAF50');

        // Text color putih untuk header
        $sheet->getStyle('A3:O3')->getFont()->getColor()->setRGB('FFFFFF');

        // Border untuk semua cell yang ada data
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A3:O' . $highestRow)->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // Hari
            'B' => 10,  // Tanggal
            'C' => 15,  // Bulan
            'D' => 8,   // Tahun
            'E' => 20,  // Nama User
            'F' => 12,  // Jam Masuk
            'G' => 15,  // Status Masuk
            'H' => 12,  // Jam Keluar
            'I' => 15,  // Status Pulang
            'J' => 15,  // Status Hari
            'K' => 12,  // Mode Kerja
            'L' => 45,  // Lokasi Absen (lebar karena alamat panjang)
            'M' => 15,  // Latitude
            'N' => 15,  // Longitude
            'O' => 15,  // Keterangan
        ];
    }
}