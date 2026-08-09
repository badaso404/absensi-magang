<?php

namespace App\Exports;

use App\Models\LaporanKegiatan;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LaporanKegiatanExport implements FromView, WithEvents, WithDrawings
{
    protected $userId;
    protected $filters;
    protected $laporan;
    protected $user; // User model yang diekspor

    /**
     * @param int|\App\Models\User $userId
     * @param array $filters
     */
    public function __construct($userId, $filters = [])
    {
        $this->userId = $userId;
        $this->filters = $filters;
    }

    /**
     * Build view for export.
     */
    public function view(): View
    {
        // Ambil laporan sesuai userId yang diberikan
        $query = LaporanKegiatan::where('user_id', $this->userId)->with('user');

        if (!empty($this->filters['month'])) {
            $query->whereMonth('tanggal', $this->filters['month']);
        }

        if (!empty($this->filters['year'])) {
            $query->whereYear('tanggal', $this->filters['year']);
        }

        $this->laporan = $query->orderBy('tanggal')->get();

        // Ambil user berdasarkan userId yang diberikan. Jangan gunakan auth() sebagai sumber utama.
        $this->user = User::find($this->userId);

        // Jika user tidak ditemukan, buat objek sederhana agar view tidak error.
        if (! $this->user) {
            $this->user = (object) [
                'name' => 'Unknown User',
                'pekerjaan' => '-',
                'bidang_suku_dinas' => '-',
            ];
        }

        $bulan = '';
        if (!empty($this->filters['month']) && !empty($this->filters['year'])) {
            $months = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            ];
            $bulan = ($this->filters['month'] && isset($months[$this->filters['month']]) ? $months[$this->filters['month']] : '') . ' ' . $this->filters['year'];
            $bulan = trim($bulan);
        }

        return view('laporan-kegiatan.export', [
            'laporan' => $this->laporan,
            'user' => $this->user,
            'bulan' => $bulan,
        ]);
    }

    /**
     * Insert gambar dokumentasi per baris.
     */
    public function drawings()
    {
        $drawings = [];
        $row = 14; // Mulai dari baris pertama data (setelah header di row 13)

        foreach ($this->laporan as $index => $item) {
            if ($item->dokumentasi && file_exists(storage_path('app/public/' . $item->dokumentasi))) {
                $drawing = new Drawing();
                $drawing->setName('Dokumentasi');
                $drawing->setDescription('Dokumentasi kegiatan');
                $drawing->setPath(storage_path('app/public/' . $item->dokumentasi));

                // Ukuran gambar (sesuaikan jika perlu)
                $drawing->setHeight(230);
                $drawing->setWidth(265);

                $drawing->setCoordinates('E' . $row); // Kolom Dokumentasi
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);

                $drawings[] = $drawing;
            }
            $row++;
        }

        return $drawings;
    }

    /**
     * Styling dan layout setelah sheet dibuat.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Tentukan baris awal data (setelah header tabel)
                $headerRow = 13;
                $dataStartRow = 14;
                $dataEndRow = $lastRow;

                // Set lebar kolom
                $sheet->getColumnDimension('A')->setWidth(4);   // NO
                $sheet->getColumnDimension('B')->setWidth(16);  // HARI/TANGGAL
                $sheet->getColumnDimension('C')->setWidth(55);  // DETAIL KEGIATAN
                $sheet->getColumnDimension('D')->setWidth(25);  // LOKASI
                $sheet->getColumnDimension('E')->setWidth(38);  // DOKUMENTASI

                // ===== STYLING HEADER INSTANSI (Baris 1-3) =====
                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('A3:E3');

                $sheet->getStyle('A1:E3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'name' => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // ===== INFO SECTION (Nama, Bidang, Pekerjaan, Bulan) - Baris 5-8 =====
                $sheet->mergeCells('A5:B5'); // Nama
                $sheet->mergeCells('A6:B6'); // Bidang
                $sheet->mergeCells('A7:B7'); // Pekerjaan
                $sheet->mergeCells('A8:B8'); // Bulan

                $sheet->mergeCells('C5:E5');
                $sheet->mergeCells('C6:E6');
                $sheet->mergeCells('C7:E7');
                $sheet->mergeCells('C8:E8');

                $sheet->getStyle('A5:B8')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'name' => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                $sheet->getStyle('C5:E8')->applyFromArray([
                    'font' => [
                        'size' => 10,
                        'name' => 'Calibri'
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // ===== HEADER TABEL (Row 13) - Abu-abu =====
                $sheet->getStyle('A' . $headerRow . ':E' . $headerRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '000000'],
                        'size' => 10,
                        'name' => 'Calibri'
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'BFBFBF']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // ===== DATA SECTION - Border & Alignment =====
                if ($dataEndRow >= $dataStartRow) {
                    $sheet->getStyle('A' . $dataStartRow . ':E' . $dataEndRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '000000']
                            ]
                        ],
                        'font' => [
                            'size' => 10,
                            'name' => 'Calibri'
                        ]
                    ]);

                    $sheet->getStyle('A' . $dataStartRow . ':A' . $dataEndRow)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER
                        ]
                    ]);

                    $sheet->getStyle('B' . $dataStartRow . ':B' . $dataEndRow)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true
                        ]
                    ]);

                    $sheet->getStyle('C' . $dataStartRow . ':C' . $dataEndRow)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true
                        ]
                    ]);

                    $sheet->getStyle('D' . $dataStartRow . ':D' . $dataEndRow)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true
                        ]
                    ]);

                    $sheet->getStyle('E' . $dataStartRow . ':E' . $dataEndRow)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER
                        ]
                    ]);

                    for ($i = $dataStartRow; $i <= $dataEndRow; $i++) {
                        $sheet->getRowDimension($i)->setRowHeight(250);
                    }
                }

                // ===== PAGE SETUP =====
                $sheet->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

                $sheet->getPageMargins()
                    ->setTop(0.75)
                    ->setRight(0.5)
                    ->setLeft(0.5)
                    ->setBottom(0.75);
            }
        ];
    }
}