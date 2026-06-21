<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[UniqueEntity(fields: ['email'], message: 'Diese E-Mail-Adresse ist bereits vergeben.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public private(set) int $id;

    #[ORM\Column(length: 180, unique: true)]
    public string $email {
        set (string $value) => $this->email = strtolower(trim($value));
    }

    /** @var list<string> */
    #[ORM\Column(type: 'json')]
    public array $roles = [];

    #[ORM\Column]
    private string $password;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /** @return list<string> */
    public function getRoles(): array
    {
        return array_unique([...$this->roles, 'ROLE_USER']);
    }

    public function getPassword(): string                 { return $this->password; }
    public function setPassword(string $password): void   { $this->password = $password; }
    public function eraseCredentials(): void              {}
}
