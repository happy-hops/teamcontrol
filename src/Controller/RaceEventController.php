<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}/events')]
#[IsGranted('ROLE_ADMIN')]
class RaceEventController extends AbstractController
{
    #[Route('', name: 'race_event_index', methods: ['GET'])]
    public function index(int $raceId): Response
    {
        // TODO: implementieren
        return $this->render('event/index.html.twig', [
            'raceId' => $raceId,
        ]);
    }
}
