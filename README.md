# Sistem Magang Kominfotik

Aplikasi absensi dan laporan kegiatan untuk peserta magang di Suku Dinas Kominfotik Jakarta Barat.

Dibangun dengan **Laravel 10** dan **PHP 8.1**.

---

## 1. Yang Perlu Diinstall Dulu

Install empat hal ini sebelum mulai. Kalau di komputermu sudah ada, lewati saja.

| Perlu | Versi | Cara cek sudah terpasang | Link download |
| --- | --- | --- | --- |
| **Git** | bebas | `git --version` | [git-scm.com](https://git-scm.com/downloads) |
| **PHP** | 8.1 atau lebih baru | `php -v` | lihat catatan di bawah |
| **Composer** | 2.x | `composer --version` | [getcomposer.org](https://getcomposer.org/download/) |
| **MySQL** | 5.7 / 8.x | `mysql --version` | lihat catatan di bawah |

> **Cara paling gampang untuk pemula:** install satu paket yang sudah berisi PHP + MySQL sekaligus.
> - **Windows** → [Laragon](https://laragon.org) (paling ramah pemula) atau [XAMPP](https://www.apachefriends.org)
> - **macOS** → [Laravel Herd](https://herd.laravel.com)
> - **Linux** → install `php8.1`, `mysql-server`, dan `composer` lewat package manager
>
> Setelah paket itu terpasang, PHP dan MySQL sudah otomatis ada. Tinggal install Composer.

### Ekstensi PHP yang wajib aktif

Cek dengan perintah ini:

```bash
php -m
```

Pastikan muncul: **gd**, **pdo_mysql**, **mbstring**, **openssl**, **fileinfo**, **xml**, **curl**, **zip**.

`gd` paling sering terlewat — tanpa itu **captcha di halaman login tidak akan muncul**. Kalau belum ada, buka file `php.ini` lalu hapus tanda `;` di depan baris `extension=gd`, kemudian restart Laragon/XAMPP/Herd.

> **Node.js / npm tidak diperlukan.** Semua CSS dan JavaScript sudah berupa file jadi di folder `public/assets`. Abaikan kalau ada tutorial yang menyuruh `npm install`.

---

## 2. Langkah Instalasi

Ikuti berurutan dari atas ke bawah. Jalankan semua perintah di terminal.

### Langkah 1 — Clone project

```bash
git clone https://github.com/badaso404/absensi-magang.git
cd absensi-magang
```

### Langkah 2 — Install dependensi Laravel

```bash
composer install
```

Tunggu sampai selesai (bisa 1–3 menit). Ini mengunduh folder `vendor/` yang tidak ikut di Git.

### Langkah 3 — Buat folder kerja Laravel

```bash
mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions bootstrap/cache
```

> Pengguna Windows (CMD), jalankan satu per satu:
> ```
> mkdir storage\framework\views
> mkdir storage\framework\cache\data
> ```

Git tidak menyimpan folder kosong, jadi dua folder ini **tidak ikut ter-clone**. Kalau dilewati, semua halaman akan error `Please provide a valid cache path`.

### Langkah 4 — Siapkan file konfigurasi

```bash
cp .env.example .env
php artisan key:generate
```

> Pengguna Windows (CMD), ganti `cp` jadi `copy`:
> `copy .env.example .env`

`php artisan key:generate` mengisi `APP_KEY`. **Tanpa ini aplikasi akan error.**

### Langkah 5 — Buat database

Buat database kosong bernama `magang_kominfotik`:

```bash
mysql -u root -p -e "CREATE DATABASE magang_kominfotik CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

> Pakai Laragon/XAMPP? Lebih gampang lewat **phpMyAdmin** (`http://localhost/phpmyadmin`) → menu **New** → ketik nama `magang_kominfotik` → **Create**.

### Langkah 6 — Sambungkan aplikasi ke database

Buka file `.env` dengan editor teks, cari bagian `DB_`, sesuaikan dengan MySQL di komputermu:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=magang_kominfotik
DB_USERNAME=root
DB_PASSWORD=
```

`DB_PASSWORD` biasanya **kosong** di Laragon/XAMPP/Herd. Isi kalau MySQL-mu pakai password.

### Langkah 7 — Buat tabel dan data awal

```bash
php artisan migrate
php artisan db:seed --class=RoleSeeder
```

Perintah kedua mengisi tabel `roles` dengan **Admin** dan **Magang**. Ini **wajib** — tanpa role, akun tidak bisa dibuat sama sekali.

> ⚠️ Jalankan `RoleSeeder` **hanya sekali saat database masih kosong**. Perintah itu mengosongkan tabel roles terlebih dahulu, jadi jangan dijalankan di database yang sudah berisi data asli.

### Langkah 8 — Sambungkan folder upload

```bash
php artisan storage:link
```

Ini membuat pintasan agar foto profil dan foto dokumentasi laporan bisa dibuka lewat browser. **Kalau dilewati, semua gambar akan gagal tampil.**

### Langkah 9 — Buat akun admin pertama

Aplikasi ini **tidak punya halaman daftar/registrasi**. Akun pertama harus dibuat manual:

```bash
php artisan tinker
```

Setelah muncul tanda `>>>`, tempel perintah berikut (ganti nama, email, dan password sesuai keinginan):

```php
\App\Models\User::create([
    'name'     => 'Nama Admin',
    'email'    => 'admin@kominfotik.test',
    'password' => 'rahasia123',
    'role_id'  => 1,
]);
```

Ketik `exit` untuk keluar.

> Password **tidak perlu** di-hash manual — Laravel mengurusnya otomatis.
> `role_id` → **1 = Admin**, **2 = Magang**.

### Langkah 10 — Jalankan aplikasi

```bash
php artisan serve
```

Buka **http://127.0.0.1:8000** di browser, lalu login pakai email dan password dari Langkah 9.

Untuk menghentikan server, tekan `Ctrl + C`.

---

## 3. Penting: GPS Hanya Jalan di HTTPS atau localhost

Fitur absensi **mewajibkan lokasi GPS**. Browser hanya mengizinkan akses lokasi pada alamat yang aman:

| Alamat | GPS jalan? |
| --- | --- |
| `http://127.0.0.1:8000` atau `http://localhost:8000` | ✅ Ya |
| `https://namasitus.test` | ✅ Ya |
| `http://namasitus.test` (HTTP biasa) | ❌ Tidak — popup izin lokasi tidak akan pernah muncul |

Jadi kalau pakai Laragon/Herd dengan domain `.test`, aktifkan HTTPS-nya dulu (di Herd: `herd secure namaproject`), atau cukup pakai `php artisan serve` yang sudah otomatis aman.

Di macOS, pastikan juga **System Settings → Privacy & Security → Location Services** mengizinkan browser yang kamu pakai.

---

## 4. Pengaturan Tambahan (Opsional)

Semuanya diatur di file `.env`. Aplikasi tetap jalan normal walau bagian ini dilewati.

| Variabel | Default | Kegunaan |
| --- | --- | --- |
| `MAGANG_JAM_MASUK` | `09:00` | Batas absen dianggap tepat waktu |
| `MAGANG_JAM_PULANG` | `15:00` | Jam pulang Senin–Kamis |
| `MAGANG_JAM_PULANG_JUMAT` | `15:30` | Jam pulang Jumat |
| `GEOAPIFY_API_KEY` | kosong | Kunci [Geoapify](https://www.geoapify.com) (gratis) untuk mengubah koordinat jadi nama tempat |
| `GEOAPIFY_TIMEOUT` | `5` | Batas waktu permintaan lokasi (detik) |
| `CAPTCHA_DISABLE` | `false` | Mematikan captcha login — **hanya untuk testing** |

Kalau `GEOAPIFY_API_KEY` dikosongkan, absensi tetap berfungsi; lokasi disimpan sebagai koordinat mentah (`Lat: -6.17, Lon: 106.82`).

---

## 5. Sebelum Dipasang di Server (Produksi)

Wajib diubah di `.env` server:

```env
APP_ENV=production
APP_DEBUG=false
```

`APP_DEBUG=true` di server akan menampilkan detail konfigurasi aplikasi ke siapa pun yang memicu error.

---

## 6. Menjalankan Test

Test memakai database terpisah agar data aslimu tidak tersentuh.

```bash
# Cukup sekali
mysql -u root -p -e "CREATE DATABASE magang_kominfotik_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

php artisan test
```

Saat ini ada **147 test**.

| Berkas | Cakupan |
| --- | --- |
| `AbsensiTest` | Alur absen pagi/sore, status telat & pulang cepat, jadwal Jumat, lokasi |
| `KeterlambatanTest` | Hitung durasi telat/pulang cepat, tahap tombol absen |
| `AksesKontrolTest` | Pembatasan admin, kepemilikan laporan, rekap milik orang lain |
| `AuthTest` | Login, batas 5 percobaan/menit, logout, ganti password |
| `LaporanKegiatanTest` | CRUD laporan, filter bulan/tahun, pengaturan |
| `KerentananTest` | Regresi seluruh temuan keamanan & bug (masa magang, GPS wajib, filter, hak akses, dll.) |

---

## 7. Fitur

**Peserta magang**

| Fitur | Keterangan |
| --- | --- |
| Absensi pagi & sore | Sekali per hari, dengan mode kerja (WFH/WFO/Dinas Luar) dan lokasi GPS wajib |
| Beranda | Grafik jam masuk/pulang sebulan, total telat dan pulang cepat |
| Rekap absensi | Filter per bulan/tahun, export Excel |
| Laporan kegiatan | Tambah/ubah/hapus laporan harian beserta foto dokumentasi, export Excel |
| Tim Saya | Melihat rekan magang satu unit untuk saling kenal |
| Profil | Ubah data diri, foto, media sosial, dan password |

**Admin** — memantau, tidak ikut absen

| Fitur | Keterangan |
| --- | --- |
| Beranda | Ringkasan harian: magang aktif, sudah absen, belum absen, telat |
| Pantau absensi | Satu baris per magang per tanggal, filter unit, navigasi antar hari |
| Laporan magang | Rekap laporan seluruh peserta, filter unit & periode, export |
| Manajemen user | Tambah, ubah, hapus peserta magang |

---

## 8. Masalah yang Sering Terjadi

| Gejala | Penyebab & solusi |
| --- | --- |
| `Please provide a valid cache path` | Folder `storage/framework/views` belum dibuat (Langkah 3) |
| `No application encryption key has been specified` | Belum jalan `php artisan key:generate` (Langkah 4) |
| `SQLSTATE[HY000] [1049] Unknown database` | Database belum dibuat atau nama di `.env` salah (Langkah 5 & 6) |
| `SQLSTATE[HY000] [1045] Access denied` | `DB_USERNAME` / `DB_PASSWORD` di `.env` tidak cocok dengan MySQL |
| Gambar captcha tidak muncul di login | Ekstensi **gd** belum aktif di `php.ini` |
| Foto profil / dokumentasi tidak tampil | Belum jalan `php artisan storage:link` (Langkah 8) |
| Tidak bisa absen, muncul "Lokasi tidak terdeteksi" | Diakses lewat HTTP biasa. Pakai `127.0.0.1:8000` atau aktifkan HTTPS (bagian 3) |
| Pesan error tampil sebagai `validation.required` | Folder `lang/` terhapus. Pastikan `lang/id/validation.php` ada |
| Sudah ubah `.env` tapi tidak berubah | Jalankan `php artisan config:clear` |

---

## 9. Struktur Folder Penting

```
app/
├── Enums/            Role (Admin=1, Magang=2), UserSeksi (ASTIK/ID/KIP/TU), AbsensiStatus
├── Http/
│   ├── Controllers/  Satu controller per fitur
│   └── Middleware/   AdminCheck, MagangAktif, HanyaMagang — penjaga hak akses
├── Models/           User, Absensi, LaporanKegiatan
└── Services/
    ├── AvatarStorage.php    Simpan/hapus foto profil
    ├── JadwalKerja.php      Sumber tunggal jam kerja & hari kerja
    └── ReverseGeocoder.php  Koordinat GPS → nama lokasi

resources/views/      Tampilan (Blade)
routes/web.php        Daftar semua halaman & hak aksesnya
lang/id/              Terjemahan pesan validasi
database/migrations/  Struktur tabel
```

### Catatan untuk yang mau ikut mengembangkan

- **Hak akses hanya diatur di `routes/web.php`** lewat middleware. Jangan menambah pengecekan `role_id` di dalam controller.
- **Jam kerja selalu dari `config/magang.php`**, jangan ditulis langsung di controller.
- **Status absensi berupa enum**, bandingkan dengan `=== AbsensiStatus::MasukTelat`, bukan angka.
- **Nama file upload selalu dibuat ulang**, jangan pakai nama asli dari pengguna.
- **Jalankan `php artisan test`** sebelum commit.

---

## 10. Backup Database

Ambil backup sebelum menjalankan migrasi di database yang berisi data asli:

```bash
mysqldump -u root -p magang_kominfotik > database/backup/dump-$(date +%Y%m%d).sql
```
