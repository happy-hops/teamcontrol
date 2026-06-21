<?php declare(strict_types=1);

namespace App\Controller\Race;

use App\Repository\RaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/current', name: 'race_current', methods: ['GET'])]
#[IsGranted('PUBLIC_ACCESS')]
final class CurrentController extends AbstractController
{
    public function __invoke(
        RaceRepository $races
    ): Response
    {
        $race = $races->findCurrent();
        return $race
            ? $this->redirectToRoute('race_show', ['id' => $race->id])
            : $this->redirectToRoute('race_index');
    }
}
