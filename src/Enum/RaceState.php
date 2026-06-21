<?php

namespace App\Enum;

enum RaceState: int
{
    case Planned  = 0;
    case Active   = 5;
    case Finished = 10;

    public function isPlanned(): bool  { return $this === self::Planned;  }
    public function isActive(): bool   { return $this === self::Active;   }
    public function isFinished(): bool { return $this === self::Finished; }

    public function label(): string
    {
        return match($this) {
            self::Planned  => 'Geplant',
            self::Active   => 'Aktiv',
            self::Finished => 'Beendet',
        };
    }
}
