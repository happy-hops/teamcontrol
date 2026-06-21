<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\TimestampableTrait;

#[ORM\Entity]
class Penalty
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

    #[ORM\Column(length: 255)]
    public string $reason;

    public function __construct(Team $team, Driver $driver, string $reason)
    {
        $this->team   = $team;
        $this->driver = $driver;
        $this->reason = $reason;
    }
}
