<?php declare(strict_types=1);

namespace App\Controller\Team;

use App\Repository\RaceRepository;
use App\Repository\TeamRepository;
use App\Stats\TeamStatsFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}/teams/{id}', name: 'team_show', requirements: ['id' => '\d+'], methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class ShowController extends AbstractController
{
    public function __invoke(
        int $raceId,
        int $id,
        RaceRepository   $races,
        TeamRepository   $teams,
        TeamStatsFactory $statsFactory
    ): Response
    {
        $race = $races->find($raceId);
        $team = $teams->find($id);
        if (!$race || !$team) throw $this->createNotFoundException();

        $stats = $statsFactory->build($team);

        return $this->render('team/show.html.twig', [
            'race'        => $race,
            'team'        => $team,
            'stats'       => $stats,
            'driverStats' => $stats->perDriver(),
            'penalties'   => [],
        ]);
    }
}
