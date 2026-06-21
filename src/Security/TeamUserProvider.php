<?php

namespace App\Security;

use App\Repository\RaceRepository;
use App\Repository\TeamRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Lädt einen TeamUser anhand seines Team-Tokens.
 *
 * Wird von Symfony in zwei Situationen aufgerufen:
 *   1. Session-Refresh: bei jedem Request prüft Symfony ob der User noch gültig ist.
 *   2. Impersonation: wenn ein Admin ?_switch_user=AB3K7P2X aufruft, lädt Symfony
 *      den TeamUser über diesen Provider.
 *
 * @implements UserProviderInterface<TeamUser>
 */
final readonly class TeamUserProvider implements UserProviderInterface
{
    public function __construct(
        private TeamRepository $teams,
        private RaceRepository $races,
    ) {}

    /**
     * Wird bei der Impersonation aufgerufen.
     * Symfony übergibt den Wert aus ?_switch_user=... als $identifier.
     */
    public function loadUserByIdentifier(string $identifier): TeamUser
    {
        $race = $this->races->findCurrent();

        if ($race === null) {
            throw new UserNotFoundException('Kein aktives Rennen gefunden.');
        }

        $team = $this->teams->findByTokenAndRace($identifier, $race);

        if ($team === null) {
            throw new UserNotFoundException(
                sprintf('Team mit Token "%s" nicht gefunden.', $identifier)
            );
        }

        return new TeamUser($team);
    }

    /**
     * Wird bei jedem Request aufgerufen um den TeamUser aus der Session zu erneuern.
     * Stellt sicher dass das Team noch existiert und das Rennen noch aktiv ist.
     */
    public function refreshUser(UserInterface $user): TeamUser
    {
        if (!$user instanceof TeamUser) {
            throw new UnsupportedUserException(
                sprintf('User vom Typ "%s" wird nicht unterstützt.', $user::class)
            );
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return $class === TeamUser::class;
    }
}
