<?php

namespace App\Entity;

use App\Enum\RaceMode;
use App\Enum\RaceState;
use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\TimestampableTrait;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
class Race
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public private(set) int $id;

    #[ORM\Column(length: 255)]
    public string $name {
        set (string $value) => $this->name = trim($value);
    }

    #[ORM\Column(length: 255, unique: true)]
    public private(set) string $slug;

    #[ORM\Column(type: 'integer', enumType: RaceState::class, options: ['default' => 0])]
    public private(set) RaceState $state = RaceState::Planned;

    #[ORM\Column(type: 'integer', enumType: RaceMode::class, options: ['default' => 0])]
    public RaceMode $mode;

    /** Gesamtrenndauer in Minuten */
    #[ORM\Column]
    public int $duration;

    /** Max. Gesamtfahrzeit eines Fahrers in Minuten */
    #[ORM\Column(name: 'max_drive')]
    public int $maxDrive;

    /** Max. Rundenlänge in Minuten */
    #[ORM\Column(name: 'max_turn')]
    public int $maxTurn;

    /** Min. Rundenlänge in Minuten */
    #[ORM\Column(name: 'min_turn')]
    public int $minTurn;

    /** Pflichtpause zwischen zwei Runden in Minuten */
    #[ORM\Column(name: 'break_time')]
    public int $breakTime;

    /** Toleranzzeit bei Überschreitung der Rundenzeit in Minuten */
    #[ORM\Column(name: 'waiting_period')]
    public int $waitingPeriod;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
    public ?\DateTimeImmutable $scheduled = null;

    #[ORM\Column(name: 'started_at', type: 'datetime_immutable', nullable: true)]
    public private(set) ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(name: 'finished_at', type: 'datetime_immutable', nullable: true)]
    public private(set) ?\DateTimeImmutable $finishedAt = null;

    #[ORM\Column(name: 'prebooking_open', options: ['default' => false])]
    public bool $prebookingOpen = false;

    #[ORM\OneToMany(mappedBy: 'race', targetEntity: Team::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    public private(set) Collection $teams;

    public function __construct(
        string   $name,
        string   $slug,
        int      $duration,
        int      $maxDrive,
        int      $maxTurn,
        int      $minTurn,
        int      $breakTime,
        int      $waitingPeriod,
        RaceMode $mode = RaceMode::Both,
    ) {
        $this->name          = $name;
        $this->slug          = $slug;
        $this->duration      = $duration;
        $this->maxDrive      = $maxDrive;
        $this->maxTurn       = $maxTurn;
        $this->minTurn       = $minTurn;
        $this->breakTime     = $breakTime;
        $this->waitingPeriod = $waitingPeriod;
        $this->mode          = $mode;
        $this->teams         = new ArrayCollection();
    }

    // --- State machine ---

    public function start(): void
    {
        if (!$this->state->isPlanned()) {
            throw new \LogicException("Rennen kann nur aus dem Status 'Geplant' gestartet werden.");
        }
        $this->state     = RaceState::Active;
        $this->startedAt = new \DateTimeImmutable();
    }

    public function finish(): void
    {
        if (!$this->state->isActive()) {
            throw new \LogicException("Rennen kann nur aus dem Status 'Aktiv' beendet werden.");
        }
        $this->state      = RaceState::Finished;
        $this->finishedAt = new \DateTimeImmutable();
    }

    // --- Helpers ---

    public function elapsedSeconds(): int
    {
        if ($this->startedAt === null) return 0;
        $end = $this->finishedAt ?? new \DateTimeImmutable();
        return $end->getTimestamp() - $this->startedAt->getTimestamp();
    }

    public function isAcceptingScans(): bool
    {
        return $this->state->isActive()
            || ($this->state->isPlanned() && $this->prebookingOpen && $this->mode->isBoth());
    }

    public function addTeam(Team $team): void
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
        }
    }
}
