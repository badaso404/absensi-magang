# Sistem Magang Kominfotik

Aplikasi absensi dan laporan kegiatan untuk peserta magang di Dinas Komunikasi, Informatika, dan Statistik (Kominfotik) Jakarta Barat.

Dibangun dengan Laravel 10 dan PHP 8.1.

---

## Fitur

**Untuk peserta magang**

| Fitur | Keterangan |
| --- | --- |
| Absensi pagi & sore | Dicatat sekali per hari, lengkap dengan mode kerja (WFH/WFO/Dinas Luar) dan lokasi GPS |
| Dashboard | Grafik jam masuk/pulang sebulan, total telat, dan total pulang cepat |
| Rekap absensi | Filter per bulan/tahun, export ke Excel |
| Laporan kegiatan | CRUD laporan harian, export ke Excel dengan kop instansi |
| Profil | Ubah data diri, foto, dan password |

**Untuk admin**

| Fitur | Keterangan |
| --- | --- |
| Manajemen user | Tambah, ubah, hapus peserta magang; filter per seksi, status, dan role |
| Absensi | Daftar peserta beserta absensi pada tanggal tertentu |
| Rekap per peserta | Buka dan export rekap absensi peserta mana pun |
| Laporan magang | Rekapitulasi laporan seluruh peserta beserta exportnya |

---

## Menjalankan Secara Lokal

Project ini dikembangkan di atas [Laravel Herd](https://herd.laravel.com) dan diakses lewat `http://magang-kominfotik.test`.

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

# Buat database, lalu sesuaikan DB_* di .env
php artisan migrate
php artisan db:seed --class=RoleSeeder

# Diperlukan agar foto profil dan dokumentasi bisa diakses
php artisan storage:link

npm run dev
```

### Konfigurasi khusus

Selain variabel Laravel standar, `.env` mengenal:

| Variabel | Default | Kegunaan |
| --- | --- | --- |
| `MAGANG_JAM_MASUK` | `09:00` | Batas absen tepat waktu |
| `MAGANG_JAM_PULANG` | `15:00` | Jam pulang Senin–Kamis |
| `MAGANG_JAM_PULANG_JUMAT` | `15:30` | Jam pulang Jumat |
| `GEOAPIFY_API_KEY` | kosong | Kunci [Geoapify](https://www.geoapify.com) untuk mengubah koordinat menjadi nama lokasi |
| `GEOAPIFY_TIMEOUT` | `5` | Timeout permintaan geocoding (detik) |
| `CAPTCHA_DISABLE` | `false` | Mematikan captcha login (dipakai saat testing) |

Semua jam kerja dibaca dari `config/magang.php`. Jangan menulis jam kerja langsung di controller — dashboard dan pencatatan absensi harus membaca sumber yang sama.

Jika `GEOAPIFY_API_KEY` kosong, absensi tetap berjalan normal; lokasi disimpan sebagai koordinat mentah.

---

## Struktur Penting

```
app/
├── Enums/
│   ├── AbsensiStatus.php    Hadir/Alpa/Izin/Sakit + TepatWaktu/Telat/PulangCepat/BelumAbsen
│   ├── Role.php             Admin = 1, Magang = 2
│   └── UserSeksi.php        ASTIK, ID, KIP, TU
├── Http/
│   ├── Controllers/         Satu controller per fitur
│   └── Middleware/
│       └── AdminCheck.php   Satu-satunya penjaga akses admin
├── Queries/
│   └── DaftarUser.php       Query daftar user + statistik (dipakai 2 halaman admin)
└── Services/
    ├── AvatarStorage.php    Simpan/hapus foto profil dengan nama file aman
    ├── JadwalKerja.php      Sumber tunggal jam masuk/pulang
    └── ReverseGeocoder.php  Koordinat GPS → nama lokasi
```

### Catatan arsitektur

- **Akses admin hanya lewat `AdminCheck`** di `routes/web.php`. Jangan menambah pengecekan `role_id != 1` di konstruktor controller — dulu logika ini tersebar di tiga tempat dan sempat berbeda-beda perilakunya.
- **Status absensi selalu berupa enum**, bukan integer. Kolom `status`, `checked_in_status`, dan `checked_out_status` di-cast ke `AbsensiStatus`, jadi bandingkan dengan case enum (`=== AbsensiStatus::MasukTelat`), bukan angka.
- **Route berparameter didaftarkan paling akhir** dalam grup `laporan-kegiatan`, supaya `/setting` dan `/export` tidak tertelan oleh `/{laporanKegiatan}`.
- **Nama file unggahan selalu di-generate ulang.** Jangan memakai `getClientOriginalName()` apa adanya.
- **Password hanya disimpan sebagai hash.** Kolom `remember_temp` tidak lagi diisi.

---

## Testing

Test memakai MySQL, bukan SQLite, karena query rekap memakai fungsi `YEAR()`.

```bash
# Sekali saja
mysql -uroot -e "CREATE DATABASE magang_kominfotik_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

php artisan test
```

Cakupan saat ini (49 test):

| Berkas | Cakupan |
| --- | --- |
| `AbsensiTest` | Alur absen pagi/sore, status telat & pulang cepat, jadwal Jumat, pencatatan lokasi, penanganan geocoding gagal |
| `KeterlambatanTest` | Kalkulasi durasi telat/pulang cepat, tahap tombol absen, konsistensi jadwal dashboard |
| `AksesKontrolTest` | Pembatasan admin, kepemilikan laporan, dan pencegahan akses rekap milik orang lain |
| `AuthTest` | Login, throttle 5 percobaan/menit, logout, ganti password |
| `LaporanKegiatanTest` | CRUD laporan, filter bulan/tahun, pengaturan pekerjaan/bidang |

---

## Database

Migrasi bersifat idempotent (memakai `Schema::hasColumn` / `hasTable`) karena skema di server sempat diubah manual di luar migrasi. Aman dijalankan ulang.

Backup terakhir ada di `database/backup/`. Ambil backup baru sebelum menjalankan migrasi di lingkungan yang berisi data asli:

```bash
mysqldump -uroot magang_kominfotik > database/backup/dump-$(date +%Y%m%d).sql
```
