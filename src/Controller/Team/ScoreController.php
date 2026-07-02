<?php declare(strict_types=1);

namespace App\Controller\Team;

use App\Repository\TeamRepository;
use App\Security\TeamUser;
use App\Stats\TeamStatsFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}/teams/{token}', name: 'team_score', requirements: ['token' => '[A-Z0-9]{8}'], methods: ['GET'])]
#[IsGranted('ROLE_TEAM', message: 'Kein Zugriff.')]
final class ScoreController extends AbstractController
{
    public function __invoke(
        int $raceId,
        string $token,
        TeamRepository $teams,
        TeamStatsFactory $statsFactory
    ): Response
    {
        $team = $teams->findByToken($token);

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

        $stats = $statsFactory->build($team);

        return $this->render('team/score.html.twig', [
            'team'        => $team,
            'race'        => $team->race,
            'stats'       => $stats,
            'driverStats' => $stats->perDriver(),
        ]);
    }
}
