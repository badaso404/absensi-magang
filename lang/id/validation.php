<?php

/*
|--------------------------------------------------------------------------
| Pesan Validasi Bahasa Indonesia
|--------------------------------------------------------------------------
|
| config/app.php memakai locale 'id' untuk locale maupun fallback_locale,
| sementara Laravel hanya membawa berkas bahasa 'en'. Tanpa berkas ini setiap
| pesan validasi yang tidak ditulis manual muncul sebagai kunci mentahnya —
| user melihat "validation.required", bukan kalimat yang bisa dibaca.
|
*/

return [
    'accepted'             => ':attribute harus disetujui.',
    'after'                => ':attribute harus berisi tanggal setelah :date.',
    'after_or_equal'       => ':attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa larik.',
    'before'               => ':attribute harus berisi tanggal sebelum :date.',
    'before_or_equal'      => ':attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'array'   => ':attribute harus memiliki antara :min dan :max item.',
        'file'    => 'Ukuran :attribute harus antara :min dan :max kilobyte.',
        'numeric' => ':attribute harus bernilai antara :min dan :max.',
        'string'  => ':attribute harus berisi antara :min dan :max karakter.',
    ],
    'boolean'              => 'Isian :attribute harus bernilai benar atau salah.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'current_password'     => 'Password saat ini salah.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'date_equals'          => ':attribute harus berisi tanggal yang sama dengan :date.',
    'date_format'          => ':attribute tidak cocok dengan format :format.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus terdiri dari :digits angka.',
    'digits_between'       => ':attribute harus terdiri dari :min sampai :max angka.',
    'dimensions'           => ':attribute memiliki dimensi gambar yang tidak valid.',
    'distinct'             => ':attribute memiliki nilai yang duplikat.',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'enum'                 => ':attribute yang dipilih tidak valid.',
    'exists'               => ':attribute yang dipilih tidak valid.',
    'file'                 => ':attribute harus berupa berkas.',
    'filled'               => 'Isian :attribute wajib diisi.',
    'gt'                   => [
        'file'    => 'Ukuran :attribute harus lebih besar dari :value kilobyte.',
        'numeric' => ':attribute harus lebih besar dari :value.',
        'string'  => ':attribute harus lebih dari :value karakter.',
    ],
    'gte'                  => [
        'file'    => 'Ukuran :attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
        'string'  => ':attribute harus lebih dari atau sama dengan :value karakter.',
    ],
    'image'                => ':attribute harus berupa gambar.',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'integer'              => ':attribute harus berupa bilangan bulat.',
    'ip'                   => ':attribute harus berupa alamat IP yang valid.',
    'json'                 => ':attribute harus berupa JSON yang valid.',
    'lt'                   => [
        'file'    => 'Ukuran :attribute harus kurang dari :value kilobyte.',
        'numeric' => ':attribute harus kurang dari :value.',
        'string'  => ':attribute harus kurang dari :value karakter.',
    ],
    'lte'                  => [
        'file'    => 'Ukuran :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'string'  => ':attribute harus kurang dari atau sama dengan :value karakter.',
    ],
    'max'                  => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
        'file'    => 'Ukuran :attribute tidak boleh lebih dari :max kilobyte.',
        'numeric' => ':attribute tidak boleh lebih besar dari :max.',
        'string'  => ':attribute tidak boleh lebih dari :max karakter.',
    ],
    'mimes'                => ':attribute harus berupa berkas berjenis: :values.',
    'mimetypes'            => ':attribute harus berupa berkas berjenis: :values.',
    'min'                  => [
        'array'   => ':attribute harus memiliki minimal :min item.',
        'file'    => 'Ukuran :attribute minimal :min kilobyte.',
        'numeric' => ':attribute minimal bernilai :min.',
        'string'  => ':attribute minimal terdiri dari :min karakter.',
    ],
    'not_in'               => ':attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'present'              => 'Isian :attribute harus ada.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':attribute wajib diisi.',
    'required_if'          => ':attribute wajib diisi bila :other adalah :value.',
    'required_unless'      => ':attribute wajib diisi kecuali :other ada di dalam :values.',
    'required_with'        => ':attribute wajib diisi bila terdapat :values.',
    'required_without'     => ':attribute wajib diisi bila tidak terdapat :values.',
    'same'                 => ':attribute dan :other harus sama.',
    'size'                 => [
        'array'   => ':attribute harus mengandung :size item.',
        'file'    => 'Ukuran :attribute harus :size kilobyte.',
        'numeric' => ':attribute harus berukuran :size.',
        'string'  => ':attribute harus berisi :size karakter.',
    ],
    'string'               => ':attribute harus berupa string.',
    'unique'               => ':attribute sudah digunakan.',
    'uploaded'             => ':attribute gagal diunggah.',
    'url'                  => 'Format :attribute tidak valid.',

    /*
    |--------------------------------------------------------------------------
    | Nama Atribut
    |--------------------------------------------------------------------------
    |
    | Supaya pesannya berbunyi "Nomor identitas (NISN/NIM) wajib diisi", bukan
    | "identity number wajib diisi".
    |
    */

    'attributes' => [
        'name'                  => 'Nama lengkap',
        'email'                 => 'Email',
        'password'              => 'Password',
        'password_confirmation' => 'Konfirmasi password',
        'current_password'      => 'Password saat ini',
        'identity_number'       => 'Nomor identitas (NISN/NIM)',
        'jenis_kelamin'         => 'Jenis kelamin',
        'tanggal_lahir'         => 'Tanggal lahir',
        'alamat'                => 'Alamat',
        'asal'                  => 'Asal sekolah/kampus',
        'jurusan'               => 'Jurusan',
        'seksi'                 => 'Seksi/unit',
        'no_telp'               => 'No. WhatsApp',
        'instagram'             => 'Instagram',
        'linkedin'              => 'LinkedIn',
        'avatar'                => 'Foto profil',
        'tanggal_awal_magang'   => 'Tanggal awal magang',
        'tanggal_akhir_magang'  => 'Tanggal akhir magang',
        'tanggal'               => 'Tanggal',
        'detail_kegiatan'       => 'Detail kegiatan',
        'lokasi'                => 'Lokasi',
        'dokumentasi'           => 'Dokumentasi',
        'pekerjaan'             => 'Pekerjaan',
        'bidang_suku_dinas'     => 'Bidang suku dinas',
        'wfhwfo'                => 'Mode kerja',
        'latitude'              => 'Lokasi',
        'longitude'             => 'Lokasi',
        'captcha'               => 'Captcha',
    ],

    'custom' => [],
];
