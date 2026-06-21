<?php declare(strict_types=1);

namespace App\Controller\Race;

use App\Repository\RaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races', name: 'race_index', methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class IndexController extends AbstractController
{
    public function __invoke(
        RaceRepository $races
    ): Response
    {
        return $this->render('race/index.html.twig', [
            'races' => $races->findAll(),
        ]);
    }
}
