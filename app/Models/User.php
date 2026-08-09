<?php

namespace App\Models;

use App\Enums\UserSeksi;
use Carbon\Carbon;
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
}