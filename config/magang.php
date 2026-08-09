<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Jam Kerja Magang
    |--------------------------------------------------------------------------
    |
    | Sumber tunggal jadwal absensi. Sebelumnya jam kerja ditulis ulang di
    | HomeController dan AbsensiController dengan nilai yang berbeda, sehingga
    | jadwal yang tampil di dashboard tidak sama dengan yang tersimpan di
    | database. Semua kode sekarang membaca dari sini.
    |
    */

    'jam_masuk' => env('MAGANG_JAM_MASUK', '09:00'),

    // Hari Senin-Kamis.
    'jam_pulang' => env('MAGANG_JAM_PULANG', '15:00'),

    // Hari Jumat, jam pulang lebih lama.
    'jam_pulang_jumat' => env('MAGANG_JAM_PULANG_JUMAT', '15:30'),

    /*
    |--------------------------------------------------------------------------
    | Reverse Geocoding (Geoapify)
    |--------------------------------------------------------------------------
    |
    | Dipakai untuk mengubah koordinat GPS saat absen menjadi nama lokasi yang
    | terbaca manusia. Jika key kosong, absensi tetap berjalan dan lokasi
    | disimpan sebagai koordinat mentah.
    |
    */

    'geoapify' => [
        'key'     => env('GEOAPIFY_API_KEY'),
        'timeout' => (int) env('GEOAPIFY_TIMEOUT', 5),
    ],

];
