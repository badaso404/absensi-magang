<?php

namespace App\Http\Controllers\Concerns;

use App\Enums\UserSeksi;
use Illuminate\Http\Request;

/**
 * Pembersih filter bulan/tahun dari query string.
 *
 * Nilai filter dipakai langsung di whereMonth()/whereYear() dan digemakan
 * kembali ke view. Tanpa pembersihan, `?year[]=2026` membuat query builder
 * menerima array dan halaman balas HTTP 500 (di server dengan APP_DEBUG=true
 * halaman error itu ikut membocorkan konfigurasi aplikasi).
 */
trait FilterPeriode
{
    /**
     * @return array{month: int|null, year: int|null}
     */
    protected function filterPeriode(Request $request): array
    {
        return [
            'month' => $this->angka($request->query('month'), 1, 12),
            'year'  => $this->angka($request->query('year'), 1900, 2999),
        ];
    }

    /**
     * Seksi/unit yang dipilih admin, atau null untuk "semua unit".
     *
     * Nilainya dicocokkan ke enum agar `?seksi[]=x` atau angka asal tidak
     * bocor ke query.
     */
    protected function filterSeksi(Request $request): ?UserSeksi
    {
        $nilai = $request->query('seksi');

        if (!is_scalar($nilai) || $nilai === '' || $nilai === 'all') {
            return null;
        }

        return UserSeksi::tryFrom((int) $nilai);
    }

    /**
     * Kembalikan integer dalam rentang yang diizinkan, atau null bila nilainya
     * kosong, berupa array, atau di luar rentang.
     */
    private function angka(mixed $nilai, int $min, int $max): ?int
    {
        if (!is_scalar($nilai) || $nilai === '' || !is_numeric($nilai)) {
            return null;
        }

        $angka = (int) $nilai;

        return $angka >= $min && $angka <= $max ? $angka : null;
    }
}
