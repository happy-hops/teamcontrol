<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\TimestampableTrait;

#[ORM\Entity]
class Station
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public private(set) int $id;

    #[ORM\Column(length: 12, unique: true)]
    public string $token {
        set (string $value) {
            $value = strtoupper(preg_replace('/\s+/', '', $value));
            if (!preg_match('/^[0-9A-F]{12}$/', $value)) {
                throw new \InvalidArgumentException('Station token muss genau 12 Hex-Zeichen sein (0-9, A-F).');
            }
            $this->token = $value;
        }
    }

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function regenerateToken(): void
    {
        $this->token = strtoupper(bin2hex(random_bytes(6)));
    }
}
