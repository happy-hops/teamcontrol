<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\TimestampableTrait;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
#[ORM\UniqueConstraint(columns: ['race_id', 'team_token'])]
class Team
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public private(set) int $id;

    #[ORM\ManyToOne(targetEntity: Race::class, inversedBy: 'teams')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) Race $race;

    #[ORM\Column(length: 255)]
    public string $name {
        set (string $value) => $this->name = trim($value);
    }

    #[ORM\Column(name: 'team_token', length: 8)]
    public private(set) string $teamToken;

    #[ORM\Column(name: 'team_lead', length: 255)]
    public string $teamLead {
        set (string $value) => $this->teamLead = trim($value);
    }

    #[ORM\Column(nullable: true)]
    public ?int $position = null;

    #[ORM\Column(name: 'logo_path', length: 255, nullable: true)]
    public ?string $logoPath = null;

    #[ORM\OneToMany(targetEntity: Attendance::class, mappedBy: 'team', cascade: ['persist', 'remove'], orphanRemoval: true)]
    public private(set) Collection $attendances;

    public function __construct(Race $race, string $name, string $teamLead)
    {
        $this->race        = $race;
        $this->name        = $name;
        $this->teamLead    = $teamLead;
        $this->teamToken   = $this->generateToken();
        $this->attendances = new ArrayCollection();
    }

    public function registerDriver(Driver $driver, ?string $tagId = null): Attendance
    {
        foreach ($this->attendances as $a) {
            if ($a->driver === $driver) {
                throw new \DomainException("Fahrer '{$driver->name}' ist bereits in Team '{$this->name}' registriert.");
            }
        }
        $attendance = new Attendance($this, $driver, $tagId);
        $this->attendances->add($attendance);
        return $attendance;
    }

    public function findAttendanceByTag(string $tagId): ?Attendance
    {
        $tagId = strtoupper(trim($tagId));
        foreach ($this->attendances as $a) {
            if ($a->tagId === $tagId) return $a;
        }
        return null;
    }

    public function claimNextUnassignedTag(string $tagId): ?Attendance
    {
        foreach ($this->attendances as $a) {
            if ($a->isUnassigned()) {
                $a->assignTag($tagId);
                return $a;
            }
        }
        return null;
    }

    private function generateToken(): string
    {
        $alphabet = str_split('23456789ABCDEFGHJKLMNPQRSTUVWXYZ');
        return implode('', array_map(
            fn () => $alphabet[random_int(0, count($alphabet) - 1)],
            range(1, 8)
        ));
    }
}
