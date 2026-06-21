<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\TimestampableTrait;

#[ORM\Entity]
class Turn
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public private(set) int $id;

    #[ORM\ManyToOne(targetEntity: Team::class)]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) Team $team;

    #[ORM\ManyToOne(targetEntity: Driver::class)]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) Driver $driver;

    /** Rundenzeit in Sekunden */
    #[ORM\Column]
    public private(set) int $duration {
        set (int $value) {
            if ($value <= 0) {
                throw new \InvalidArgumentException('Rundenzeit muss größer als 0 Sekunden sein.');
            }
            $this->duration = $value;
        }
    }

    public function __construct(Team $team, Driver $driver, int $durationSeconds)
    {
        $this->team     = $team;
        $this->driver   = $driver;
        $this->duration = $durationSeconds;
    }

    public function reduceDurationBy(int $seconds): void
    {
        $this->duration = $this->duration - $seconds;
    }
}
