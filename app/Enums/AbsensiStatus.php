<?php

namespace App\Enums;

enum AbsensiStatus: int implements EnumText 
{
    use EnumArray;

    case Hadir                     = 1;
    case Alpa                      = 2;
    case Izin                      = 3;
    case Sakit                     = 4;

    case TepatWaktu                = 5;
    case MasukTelat                = 6;
    case PulangCepat               = 7;
    case BelumAbsen                = 8;

    public function text(): string {
        return match($this) {
            self::Hadir                 => 'Hadir',
            self::Alpa                  => 'Alpa',
            self::Izin                  => 'Izin',
            self::Sakit                 => 'Sakit',
            
            self::TepatWaktu            => 'Tepat Waktu',
            self::MasukTelat            => 'Masuk Telat',
            self::PulangCepat           => 'Pulang Cepat',
            self::BelumAbsen            => 'Belum Absen',
        };
    }

    public function color(): string {
        return match($this) {
            self::Hadir                 => 'success',
            self::Alpa                  => 'danger',
            self::Izin                  => 'warning',
            self::Sakit                 => 'info',
            
            self::TepatWaktu            => 'success',
            self::MasukTelat            => 'warning',
            self::PulangCepat           => 'warning',
            self::BelumAbsen            => 'danger',
        };
    }

    // Tambahkan icon() agar view dapat memanggil ->icon()
    public function icon(): string
    {
        return match($this) {
            self::Hadir        => 'fa-check-circle',
            self::Alpa         => 'fa-times-circle',
            self::Izin         => 'fa-user-clock',
            self::Sakit        => 'fa-notes-medical',

            self::TepatWaktu   => 'fa-clock',
            self::MasukTelat   => 'fa-hourglass-half',
            self::PulangCepat  => 'fa-sign-out-alt',
            self::BelumAbsen   => 'fa-question-circle',
        };
    }
}