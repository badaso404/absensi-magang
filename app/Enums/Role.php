<?php

namespace App\Enums;

enum Role: int implements EnumText 
{
    use EnumArray;

    case Admin                     = 1;
    case Magang                    = 2;

    public function text(): string {
        return match($this) {
            self::Admin                 => 'Admin',
            self::Magang                => 'Magang',
        };
    }
}