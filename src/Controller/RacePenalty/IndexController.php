<?php

namespace App\Controller\RacePenalty;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}/penalties', name: 'race_penalty_index', methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class IndexController extends AbstractController
{
    public function __invoke(
        int $raceId,
    ): Response
    {
        return $this->render('penalty/index.html.twig', [
            'raceId' => $raceId,
        ]);
    }
}
