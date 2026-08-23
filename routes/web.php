<?php

use App\Http\Controllers\{
    AbsensiController,
    AdminAbsensiController,
    AdminUserController,
    AuthController,
    HomeController,
    ProfilController,
    RekapabsenController,
    LaporanKegiatanController,
    AdminLaporanController,
    TimController
};
use App\Http\Middleware\{AdminCheck, Authenticate, HanyaMagang, MagangAktif, Unauthenticated};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Magang Kominfotik
|--------------------------------------------------------------------------
*/

// --- GRUP: USER BELUM LOGIN (GUEST) ---
Route::middleware(Unauthenticated::class)->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');

    // Throttle: maksimal 5 percobaan login per menit per IP, mencegah brute force.
    Route::post('/login-attempt', [AuthController::class, 'loginAttempt'])
        ->middleware('throttle:5,1')
        ->name('login-attempt');

    Route::get('/refresh-captcha', [AuthController::class, 'refreshCaptcha'])->name('refresh-captcha');
});

// --- GRUP: USER SUDAH LOGIN (AUTH) ---
// MagangAktif memutus sesi magang yang masa magangnya sudah lewat, termasuk
// sesi lama yang dibuat sebelum tanggal akhir magang terlampaui.
Route::middleware([Authenticate::class, MagangAktif::class])->group(function () {

    // Autentikasi Dasar
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // --- FITUR: ABSENSI (peserta magang saja) ---
    // Admin hanya memantau; absensinya sendiri tidak dicatat di sistem ini.
    Route::middleware(HanyaMagang::class)->group(function () {
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
        Route::post('/absen/{tipe}', [AbsensiController::class, 'absen'])->name('absen');

        // Perkenalan antar peserta satu unit. Admin sudah punya menu Users.
        Route::get('/tim', [TimController::class, 'index'])->name('tim');
    });

    // --- FITUR: PROFIL ---
    Route::prefix('profil')->name('profil')->group(function () {
        Route::get('/', [ProfilController::class, 'index']); // route('profil')
        Route::get('/edit', [ProfilController::class, 'editProfil'])->name('-edit');
        Route::post('/update', [ProfilController::class, 'updateProfil'])->name('-update');
        Route::post('/update-password', [ProfilController::class, 'updateProfilPassword'])->name('-update-password');
    });

    // --- FITUR: REKAP ABSEN ---
    Route::prefix('rekapabsen')->name('rekapabsen')->group(function () {
        Route::get('/export', [RekapabsenController::class, 'export'])->name('.export');
        Route::get('/', [RekapabsenController::class, 'index']); // route('rekapabsen')

        // Rekap milik user lain hanya boleh dibuka admin. Tanpa AdminCheck,
        // user magang bisa membaca absensi siapa pun cukup dengan mengganti ID.
        Route::middleware(AdminCheck::class)->group(function () {
            Route::get('/{user}', [RekapabsenController::class, 'index'])->name('.user');
            Route::get('/{user}/export', [RekapabsenController::class, 'export'])->name('.user-export');
        });
    });

    // --- FITUR: LAPORAN KEGIATAN (CRUD, peserta magang saja) ---
    // Sama seperti absensi: admin menilai laporan magang lewat menu admin,
    // bukan membuat laporan kegiatannya sendiri.
    Route::middleware(HanyaMagang::class)->prefix('laporan-kegiatan')->name('laporan-kegiatan.')->group(function () {
        Route::get('/', [LaporanKegiatanController::class, 'index'])->name('index');
        Route::get('/create', [LaporanKegiatanController::class, 'create'])->name('create');
        Route::post('/', [LaporanKegiatanController::class, 'store'])->name('store');
        Route::get('/export', [LaporanKegiatanController::class, 'export'])->name('export');

        // Pengaturan Laporan (Pekerjaan/Bidang)
        Route::get('/setting', [LaporanKegiatanController::class, 'setting'])->name('setting');
        Route::put('/setting', [LaporanKegiatanController::class, 'settingUpdate'])->name('setting.update');

        // Route dengan parameter didaftarkan terakhir agar tidak menelan
        // /create, /export, dan /setting.
        Route::get('/{laporanKegiatan}/edit', [LaporanKegiatanController::class, 'edit'])->name('edit');
        Route::put('/{laporanKegiatan}', [LaporanKegiatanController::class, 'update'])->name('update');
        Route::delete('/{laporanKegiatan}', [LaporanKegiatanController::class, 'destroy'])->name('destroy');
    });

    // --- GRUP: ADMIN ONLY ---
    Route::middleware(AdminCheck::class)->prefix('admin')->name('admin-')->group(function () {

        // Manajemen User
        Route::prefix('user')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('user');
            Route::get('/create', [AdminUserController::class, 'create'])->name('user-create');
            Route::post('/', [AdminUserController::class, 'store'])->name('user-store');
            Route::get('/{id}/edit', [AdminUserController::class, 'edit'])->name('user-edit');
            Route::put('/{id}', [AdminUserController::class, 'update'])->name('user-update');
            Route::delete('/{id}', [AdminUserController::class, 'destroy'])->name('user-destroy');
        });

        // Manajemen Absensi
        Route::get('/absensi', [AdminAbsensiController::class, 'index'])->name('absensi');

        // Laporan Magang Seluruh User
        Route::prefix('laporan-magangs')->name('laporan.')->group(function () {
            Route::get('/', [AdminLaporanController::class, 'index'])->name('index');
            Route::get('/{user}', [AdminLaporanController::class, 'show'])->name('user');
            Route::get('/{user}/export', [AdminLaporanController::class, 'export'])->name('export');
        });
    });
});
