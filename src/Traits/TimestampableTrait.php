<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Ersetzt Gedmos TimestampableEntity-Trait.
 * Nutzt DateTimeImmutable statt DateTime.
 */
trait TimestampableTrait
{
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    public private(set) \DateTimeImmutable $createdAt;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable')]
    public private(set) \DateTimeImmutable $updatedAt;
}
