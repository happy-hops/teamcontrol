<?php declare(strict_types=1);

namespace App\Controller\Race;

use App\Entity\Race;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{id}/public', name: 'race_public_overview', requirements: ['id' => '\d+'], methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class PublicOverviewController extends AbstractController
{
    #[IsGranted('PUBLIC_ACCESS')]
    public function __invoke(
        Race $race
    ): Response
    {
        return $this->render('race/public_overview.html.twig', [
            'race'  => $race,
            'teams' => $race->teams,
        ]);
    }
}
