<?php

namespace App\Enum;

enum EventMode: int
{
    case Arriving = 1;
    case Leaving  = 2;

    public function isArriving(): bool { return $this === self::Arriving; }
    public function isLeaving(): bool  { return $this === self::Leaving;  }

    public function label(): string
    {
        return match($this) {
            self::Arriving => 'Einfahrt',
            self::Leaving  => 'Ausfahrt',
        };
    }
}
