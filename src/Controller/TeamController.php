<?php

namespace App\Controller;

use App\Entity\Race;
use App\Repository\RaceRepository;
use App\Repository\TeamRepository;
use App\Security\TeamUser;
use App\Stats\TeamStatsFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}/teams')]
class TeamController extends AbstractController
{
    public function __construct(
        private readonly RaceRepository   $races,
        private readonly TeamRepository   $teams,
        private readonly TeamStatsFactory $statsFactory,
    ) {}

    #[Route('', name: 'team_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(int $raceId): Response
    {
        $race = $this->races->find($raceId);
        if (!$race) throw $this->createNotFoundException();

        return $this->render('team/index.html.twig', [
            'race'  => $race,
            'teams' => $race->teams->toArray(),
        ]);
    }

    #[Route('/{id}', name: 'team_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(int $raceId, int $id): Response
    {
        $race = $this->races->find($raceId);
        $team = $this->teams->find($id);
        if (!$race || !$team) throw $this->createNotFoundException();

        $stats = $this->statsFactory->build($team);

        return $this->render('team/show.html.twig', [
            'race'        => $race,
            'team'        => $team,
            'stats'       => $stats,
            'driverStats' => $stats->perDriver(),
            'penalties'   => [],
        ]);
    }

    /**
     * Team-Score-Seite.
     * Zugänglich für ROLE_TEAM (nur eigenes Team) und ROLE_ADMIN.
     */
    #[Route('/{token}', name: 'team_score', methods: ['GET'], requirements: ['token' => '[A-Z0-9]{8}'])]
    #[IsGranted('ROLE_TEAM', message: 'Kein Zugriff.')]
    public function score(int $raceId, string $token): Response
    {
        $team = $this->teams->findByToken($token);

        if ($team === null || $team->race->id !== $raceId) {
            throw $this->createNotFoundException('Team nicht gefunden.');
        }

        if (!$this->isGranted('ROLE_ADMIN')) {
            /** @var TeamUser $user */
            $user = $this->getUser();
            if ($user->getUserIdentifier() !== $token) {
                throw $this->createAccessDeniedException();
            }
        }

        $stats = $this->statsFactory->build($team);

        return $this->render('team/score.html.twig', [
            'team'        => $team,
            'race'        => $team->race,
            'stats'       => $stats,
            'driverStats' => $stats->perDriver(),
        ]);
    }
}
