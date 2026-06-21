<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\TimestampableTrait;

#[ORM\Entity]
#[ORM\Index(columns: ['tag_id'])]
class Attendance
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public private(set) int $id;

    #[ORM\ManyToOne(targetEntity: Team::class, inversedBy: 'attendances')]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) Team $team;

    #[ORM\ManyToOne(targetEntity: Driver::class)]
    #[ORM\JoinColumn(nullable: false)]
    public private(set) Driver $driver;

    #[ORM\Column(name: 'tag_id', length: 255, nullable: true)]
    public private(set) ?string $tagId = null;

    public function __construct(Team $team, Driver $driver, ?string $tagId = null)
    {
        $this->team   = $team;
        $this->driver = $driver;
        if ($tagId !== null) {
            $this->tagId = strtoupper(trim($tagId));
        }
    }

    public function isUnassigned(): bool { return $this->tagId === null || $this->tagId === ''; }

    public function assignTag(string $tagId): void
    {
        if (!$this->isUnassigned()) {
            throw new \DomainException("Attendance #{$this->id} hat bereits einen Tag zugewiesen.");
        }
        $this->tagId = strtoupper(trim($tagId));
    }
}
