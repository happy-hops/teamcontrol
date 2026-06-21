<?php

namespace App\Security;

use App\Entity\Team;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Repräsentiert ein eingeloggtes Team ohne echten User-Account.
 * Wird vom TeamTokenAuthenticator erstellt und in der Session gehalten.
 * getUserIdentifier() gibt den Team-Token zurück — eindeutig innerhalb eines Rennens.
 */
final readonly class TeamUser implements UserInterface
{
    public function __construct(
        private Team $team,
    ) {}

    public function getTeam(): Team
    {
        return $this->team;
    }

    /**
     * Der Token dient als eindeutiger Identifier in der Session.
     */
    public function getUserIdentifier(): string
    {
        return $this->team->teamToken;
    }

    /**
     * @return list<string>
     */
    public function getRoles(): array
    {
        return ['ROLE_TEAM'];
    }

    public function getPassword(): null
    {
        return null;
    }

    public function eraseCredentials(): void {}
}
