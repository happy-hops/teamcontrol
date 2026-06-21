<?php

namespace App\Entity;

use App\Repository\DriverRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\TimestampableTrait;

#[ORM\Entity(repositoryClass: DriverRepository::class)]
class Driver
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public private(set) int $id;

    #[ORM\Column(length: 255)]
    public string $name {
        set (string $value) {
            $value = trim($value);
            if ($value === '') {
                throw new \InvalidArgumentException('Driver name must not be empty.');
            }
            $this->name = $value;
        }
    }

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
