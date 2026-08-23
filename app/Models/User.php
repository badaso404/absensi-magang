<?php

namespace App\Models;

use App\Enums\UserSeksi;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'identity_number', // Penambahan NISN/NIM
        'avatar',
        'no_telp',
        'alamat',
        'jenis_kelamin',
        'tanggal_lahir',
        'seksi',
        'asal',
        'jurusan',
        'instagram',
        'linkedin',
        'tanggal_awal_magang',
        'tanggal_akhir_magang',
        'remember_temp',
        'pekerjaan',          
        'bidang_suku_dinas',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'seksi' => UserSeksi::class,
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Check if user magang is still active
     */
    public function isActive(): bool
    {
        if (!$this->tanggal_akhir_magang) {
            return true; // Jika tidak ada tanggal akhir, dianggap aktif
        }

        return Carbon::parse($this->tanggal_akhir_magang)->isFuture() || 
               Carbon::parse($this->tanggal_akhir_magang)->isToday();
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return $this->isActive() ? 'badge-success' : 'badge-danger';
    }

    /**
     * Get status text
     */
    public function getStatusText(): string
    {
        return $this->isActive() ? 'Aktif' : 'Tidak Aktif';
    }

    /**
     * Scope untuk filter user aktif
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('tanggal_akhir_magang')
              ->orWhere('tanggal_akhir_magang', '>=', Carbon::today());
        });
    }

    /**
     * Scope untuk filter user tidak aktif
     */
    public function scopeInactive($query)
    {
        return $query->whereNotNull('tanggal_akhir_magang')
                     ->where('tanggal_akhir_magang', '<', Carbon::today());
    }

    /**
     * User yang masa magangnya mencakup tanggal tertentu.
     *
     * Dipakai papan pantau absensi harian. scopeActive() tidak bisa dipakai di
     * sana karena hanya melihat tanggal_akhir_magang dan selalu diukur
     * terhadap HARI INI, sehingga:
     *
     * - magang yang baru mulai Agustus ikut muncul "Belum Absen" saat admin
     *   membuka tanggal sebelum dia masuk, padahal saat itu dia belum magang;
     * - magang yang sudah selesai justru hilang dari tanggal-tanggal saat dia
     *   masih aktif, sehingga riwayatnya tampak bolong.
     *
     * Tanggal yang NULL diperlakukan sebagai "tidak dibatasi".
     */
    public function scopeAktifPada($query, CarbonInterface $tanggal)
    {
        return $query->aktifDalamRentang($tanggal, $tanggal);
    }

    /**
     * User yang masa magangnya bersinggungan dengan rentang tanggal tertentu.
     *
     * Dipakai halaman laporan magang yang difilter per bulan: yang dicari
     * adalah siapa saja yang magang SELAMA bulan itu, bukan siapa yang magang
     * hari ini. Tanpa ini, memfilter ke bulan lampau menampilkan angkatan
     * sekarang (yang laporannya nol) dan menyembunyikan angkatan yang benar-
     * benar menulis laporan pada bulan tersebut.
     *
     * Bersinggungan = mulai sebelum rentang berakhir DAN selesai setelah
     * rentang dimulai. Tanggal NULL berarti tidak dibatasi.
     */
    public function scopeAktifDalamRentang($query, CarbonInterface $mulai, CarbonInterface $selesai)
    {
        return $query
            ->where(function ($q) use ($selesai) {
                $q->whereNull('tanggal_awal_magang')
                  ->orWhereDate('tanggal_awal_magang', '<=', $selesai);
            })
            ->where(function ($q) use ($mulai) {
                $q->whereNull('tanggal_akhir_magang')
                  ->orWhereDate('tanggal_akhir_magang', '>=', $mulai);
            });
    }
}