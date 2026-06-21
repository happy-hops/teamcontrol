<?php

namespace App\Controller\RaceEvent;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{raceId}/events', name: 'race_event_index', methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class RaceEventController extends AbstractController
{
    public function __invoke(int $raceId): Response
    {
        // TODO: implementieren
        return $this->render('event/index.html.twig', [
            'raceId' => $raceId,
        ]);
    }
}
