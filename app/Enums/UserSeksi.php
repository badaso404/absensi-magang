<?php

namespace App\Enums;

enum UserSeksi: int implements EnumText 
{
    use EnumArray;

    case ASTIK  = 1;
    case ID     = 2;
    case KIP    = 3;
    case TU     = 4;

    public function text(): string {
        return match($this) {
            self::ASTIK       => 'Aplikasi Siber dan Statistik',
            self::ID          => 'Infrastruktur Digital',
            self::KIP         => 'Komunikasi dan Informasi Publik',
            self::TU          => 'Tata Usaha',
        };
    }

    public function color(): string {
        return match($this) {
            self::ASTIK   => 'success',
            self::ID      => 'danger',
            self::KIP     => 'warning',
            self::TU      => 'info',
        };
    }

    public function code(): string {
        return match($this) {
            self::ASTIK   => 'ASTIK',
            self::ID      => 'ID',
            self::KIP     => 'KIP',
            self::TU      => 'TU',
        };
    }
}