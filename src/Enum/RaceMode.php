<?php

namespace App\Enum;

enum RaceMode: int
{
    case Both    = 0;
    case Leaving = 5;

    public function isBoth(): bool    { return $this === self::Both;    }
    public function isLeaving(): bool { return $this === self::Leaving; }

    public function label(): string
    {
        return match($this) {
            self::Both    => 'Ein- & Ausfahrt',
            self::Leaving => 'Nur Ausfahrt',
        };
    }
}
