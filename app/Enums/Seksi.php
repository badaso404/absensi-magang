<?php

namespace App\Enums;

enum Seksi: int
{
    case ASTIK = 1;
    case ID = 2;
    case KIP = 3;
    case TU = 4;

    public function name(): string
    {
        return match($this) {
            self::ASTIK => 'ASTIK',
            self::ID => 'ID',
            self::KIP => 'KIP',
            self::TU => 'TU',
        };
    }

    public function code(): string
    {
        return match($this) {
            self::ASTIK => 'AST',
            self::ID => 'ID',
            self::KIP => 'KIP',
            self::TU => 'TU',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ASTIK => 'primary',
            self::ID => 'success',
            self::KIP => 'info',
            self::TU => 'warning',
        };
    }
}
