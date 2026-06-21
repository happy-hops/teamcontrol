<?php

namespace App\Entity;

use App\Enum\EventMode;
use App\Repository\RaceEventRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\TimestampableTrait;

#[ORM\Entity(repositoryClass: RaceEventRepository::class)]
class RaceEvent
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

    #[ORM\Column(type: 'integer', enumType: EventMode::class)]
    public private(set) EventMode $mode;

    #[ORM\OneToOne(targetEntity: Turn::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    public private(set) ?Turn $turn = null;

    #[ORM\OneToOne(targetEntity: Penalty::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    public private(set) ?Penalty $penalty = null;

    public function __construct(Team $team, Driver $driver, EventMode $mode)
    {
        $this->team   = $team;
        $this->driver = $driver;
        $this->mode   = $mode;
    }

    public function assignTurn(Turn $turn): void       { $this->turn    = $turn;    }
    public function assignPenalty(Penalty $p): void    { $this->penalty = $p;       }
    public function removePenalty(): void              { $this->penalty = null;     }

    public function isArriving(): bool { return $this->mode->isArriving(); }
    public function isLeaving(): bool  { return $this->mode->isLeaving();  }
}
