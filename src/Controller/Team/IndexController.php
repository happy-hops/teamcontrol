<?php declare(strict_types=1);

namespace App\Controller\Team;

use App\Repository\RaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}/teams', name: 'team_index', methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class IndexController extends AbstractController
{
    public function __construct(
        private readonly RaceRepository   $races,
    ) {}

    public function __invoke(
        int $raceId
    ): Response
    {
        $race = $this->races->find($raceId);
        if (!$race) throw $this->createNotFoundException();

        return $this->render('team/index.html.twig', [
            'race'  => $race,
            'teams' => $race->teams->toArray(),
        ]);
    }
}
